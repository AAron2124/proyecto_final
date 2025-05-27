<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

// Verificar que el usuario está logueado y es alumno
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'alumno') {
    header("Location: ../views/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos del alumno
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE usuario_id = :usuario_id");
$stmt->execute(['usuario_id' => $usuario_id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    echo "<div class='alert alert-danger'>No se encontró información del alumno.</div>";
    exit;
}

// Obtener calificaciones del alumno con info de materia, profesor y grupo
$sql = "
SELECT c.calificacion, c.fecha, m.nombre AS materia, 
       CONCAT(p.nombre, ' ', p.apellido) AS profesor, g.nombre AS grupo
FROM calificaciones c
JOIN asignaciones a ON c.asignacion_id = a.id
JOIN materias m ON a.materia_id = m.id
JOIN profesores p ON a.profesor_id = p.id
JOIN grupos g ON a.grupo_id = g.id
WHERE c.alumno_id = :alumno_id
ORDER BY c.fecha DESC
";

$stmt2 = $pdo->prepare($sql);
$stmt2->execute(['alumno_id' => $alumno['id']]);
$calificaciones = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Bienvenido, <?= htmlspecialchars($alumno['nombre']) ?></h2>

<h3>Tus calificaciones</h3>
<?php if ($calificaciones): ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Materia</th>
            <th>Profesor</th>
            <th>Grupo</th>
            <th>Calificación</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($calificaciones as $cal): ?>
        <tr>
            <td><?= htmlspecialchars($cal['materia']) ?></td>
            <td><?= htmlspecialchars($cal['profesor']) ?></td>
            <td><?= htmlspecialchars($cal['grupo']) ?></td>
            <td><?= htmlspecialchars($cal['calificacion']) ?></td>
            <td><?= htmlspecialchars($cal['fecha']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>No tienes calificaciones registradas.</p>
<?php endif; ?>

<div class="mt-4">
   <a href="../views/logout.php" class="btn btn-danger">Cerrar sesión</a>

</div>

<?php require '../includes/footer.php'; ?>
