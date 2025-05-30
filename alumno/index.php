<?php
session_start();
// Hola yo soy Mateo
require '../includes/db.php';
require '../includes/header.php';

function verificarLogin() {
    if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
        header("Location: ../views/login.php");
        exit;
    }
}
verificarLogin();

// Validar que exista usuario_id en sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "<div class='alert alert-danger'>Sesión inválida. Por favor, vuelve a iniciar sesión.</div>";
    require '../includes/footer.php';
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar al alumno correspondiente
$stmt = $pdo->prepare("SELECT id, nombre, apellido FROM alumnos WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    echo "<div class='alert alert-danger'>No se encontró el alumno vinculado a esta cuenta.</div>";
    require '../includes/footer.php';
    exit;
}

// Obtener calificaciones del alumno
$sql = "SELECT 
            c.calificacion, 
            c.fecha, 
            c.comentario,
            m.nombre AS materia,
            g.nombre AS grupo,
            p.nombre AS profesor_nombre,
            p.apellido AS profesor_apellido
        FROM calificaciones c
        JOIN materias m ON c.materia_id = m.id
        JOIN grupos g ON c.grupo_id = g.id
        JOIN profesores p ON c.profesor_id = p.id
        WHERE c.alumno_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$alumno['id']]);
$calificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">Bienvenido, <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']) ?></h2>

<h4 class="mb-3">Tus Calificaciones 📚</h4>

<?php if (count($calificaciones) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Profesor</th>
                    <th>Calificación</th>
                    <th>Fecha</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($calificaciones as $cal): ?>
                    <tr>
                        <td><?= htmlspecialchars($cal['materia']) ?></td>
                        <td><?= htmlspecialchars($cal['grupo']) ?></td>
                        <td><?= htmlspecialchars($cal['profesor_nombre'] . ' ' . $cal['profesor_apellido']) ?></td>
                        <td><?= htmlspecialchars($cal['calificacion']) ?></td>
                        <td><?= htmlspecialchars($cal['fecha']) ?></td>
                        <td><?= htmlspecialchars($cal['comentario']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">Aún no tienes calificaciones registradas.</div>
<?php endif; ?>

<?php require '../includes/footer.php'; ?>