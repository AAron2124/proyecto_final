<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Consulta para traer todas las calificaciones con info relacionada
$sql = "SELECT c.id, a.nombre AS alumno_nombre, a.apellido AS alumno_apellido, 
        m.nombre AS materia_nombre, g.nombre AS grupo_nombre, 
        p.nombre AS profesor_nombre, p.apellido AS profesor_apellido, 
        c.calificacion
        FROM calificaciones c
        JOIN alumnos a ON c.alumno_id = a.id
        JOIN materias m ON c.materia_id = m.id
        JOIN grupos g ON c.grupo_id = g.id
        JOIN profesores p ON c.profesor_id = p.id
        ORDER BY a.apellido, a.nombre, m.nombre";

$stmt = $pdo->query($sql);
?>

<h2 class="mb-4">Lista de Calificaciones</h2>

<a href="create.php" class="btn btn-primary mb-3">Agregar Calificación</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Alumno</th>
            <th>Materia</th>
            <th>Grupo</th>
            <th>Profesor</th>
            <th>Calificación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetch()): ?>
        <tr>
            <td><?= htmlspecialchars($row['alumno_nombre'] . ' ' . $row['alumno_apellido']) ?></td>
            <td><?= htmlspecialchars($row['materia_nombre']) ?></td>
            <td><?= htmlspecialchars($row['grupo_nombre']) ?></td>
            <td><?= htmlspecialchars($row['profesor_nombre'] . ' ' . $row['profesor_apellido']) ?></td>
            <td><?= htmlspecialchars($row['calificacion']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <!-- Aquí podrías agregar eliminar si quieres -->
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php require '../../includes/footer.php'; ?>
