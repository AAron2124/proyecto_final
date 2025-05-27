<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
    header("Location: ../views/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT id, nombre, apellido FROM alumnos WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    echo "<div class='alert alert-danger'>No se encontró el alumno vinculado a este usuario.</div>";
    require '../includes/footer.php';
    exit;
}

$alumno_id = $alumno['id'];

$stmt = $pdo->prepare("
    SELECT 
        m.nombre AS materia,
        m.descripcion,
        p.nombre AS profesor_nombre,
        p.apellido AS profesor_apellido,
        g.nombre AS grupo,
        g.nivel,
        c.calificacion,
        c.fecha
    FROM calificaciones c
    JOIN asignaciones a ON c.asignacion_id = a.id
    JOIN materias m ON a.materia_id = m.id
    JOIN profesores p ON a.profesor_id = p.id
    JOIN grupos g ON a.grupo_id = g.id
    WHERE c.alumno_id = ?
    ORDER BY c.fecha DESC
");
$stmt->execute([$alumno_id]);
$calificaciones = $stmt->fetchAll();
?>

<h2 class="mb-4">Hola, <?= htmlspecialchars($alumno['nombre']) . ' ' . htmlspecialchars($alumno['apellido']) ?></h2>

<h4 class="mb-3">Mis Calificaciones</h4>

<?php if (count($calificaciones) === 0): ?>
    <div class="alert alert-info">Aún no tienes calificaciones registradas.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Materia</th>
                    <th>Descripción</th>
                    <th>Profesor</th>
                    <th>Grupo</th>
                    <th>Calificación</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($calificaciones as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['materia']) ?></td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                        <td><?= htmlspecialchars($fila['profesor_nombre']) . ' ' . htmlspecialchars($fila['profesor_apellido']) ?></td>
                        <td><?= htmlspecialchars($fila['grupo']) . ' (' . htmlspecialchars($fila['nivel']) . ')' ?></td>
                        <td><strong><?= htmlspecialchars($fila['calificacion']) ?></strong></td>
                        <td><?= htmlspecialchars($fila['fecha']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<a href="index.php" class="btn btn-secondary mt-4"><i class="bi bi-arrow-left"></i> Volver al panel</a>

<?php require '../includes/footer.php'; ?>
