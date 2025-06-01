<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Consulta para obtener todas las calificaciones con sus relaciones
$sql = "SELECT c.id, c.calificacion, c.fecha,
        a.nombre AS nombre_alumno, a.apellido AS apellido_alumno,
        g.nombre AS nombre_grupo,
        m.nombre AS nombre_materia,
        p.nombre AS nombre_profesor, p.apellido AS apellido_profesor
        FROM calificaciones c
        INNER JOIN alumnos a ON c.alumno_id = a.id
        INNER JOIN grupos g ON c.grupo_id = g.id
        INNER JOIN materias m ON c.materia_id = m.id
        INNER JOIN profesores p ON c.profesor_id = p.id
        ORDER BY c.fecha DESC";

$stmt = $pdo->query($sql);
$calificaciones = $stmt->fetchAll();
?>

<h2 class="mb-4">Calificaciones</h2>

<a href="crear.php" class="btn btn-primary mb-3">Agregar Calificación</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Alumno</th>
            <th>Especialidad</th>
            <th>Materia</th>
            <th>Profesor</th>
            <th>Calificación</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($calificaciones as $cal): ?>
            <tr>
                <td><?= htmlspecialchars($cal['nombre_alumno'] . ' ' . $cal['apellido_alumno']) ?></td>
                <td><?= htmlspecialchars($cal['nombre_grupo']) ?></td>
                <td><?= htmlspecialchars($cal['nombre_materia']) ?></td>
                <td><?= htmlspecialchars($cal['nombre_profesor'] . ' ' . $cal['apellido_profesor']) ?></td>
                <td><?= htmlspecialchars($cal['calificacion']) ?></td>
                <td><?= htmlspecialchars($cal['fecha']) ?></td>
                <td>
                    <a href="editar.php?id=<?= $cal['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="eliminar.php?id=<?= $cal['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta calificación?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($calificaciones) === 0): ?>
            <tr>
                <td colspan="7" class="text-center">No hay calificaciones registradas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require '../../includes/footer.php'; ?>
