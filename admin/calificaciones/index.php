<?php
require '../../includes/db.php';

$sql = "SELECT c.id, a.nombre AS alumno, g.nombre AS grupo, c.calificacion
        FROM calificaciones c
        JOIN alumnos a ON c.id_alumno = a.id
        JOIN grupos g ON c.id_grupo = g.id";

$stmt = $pdo->query($sql);
$calificaciones = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calificaciones</title>
</head>
<body>
<h1>Listado de Calificaciones</h1>
<a href="crear.php">Agregar Calificación</a>
<table>
    <tr>
        <th>Alumno</th><th>Grupo</th><th>Calificación</th><th>Acciones</th>
    </tr>
    <?php foreach ($calificaciones as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['alumno']) ?></td>
            <td><?= htmlspecialchars($c['grupo']) ?></td>
            <td><?= htmlspecialchars($c['calificacion']) ?></td>
            <td>
                <a href="editar.php?id=<?= $c['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $c['id'] ?>" onclick="return confirmarEliminacion()">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script src="../../js/confirmaciones.js"></script>
</body>
</html>
