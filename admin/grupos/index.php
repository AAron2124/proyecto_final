<?php
include '../../includes/db.php';

$sql = "SELECT g.id, g.nombre, g.horario, m.nombre AS materia, p.nombre AS profesor 
        FROM grupos g 
        JOIN materias m ON g.id_materia = m.id
        JOIN profesores p ON g.id_profesor = p.id";

$stmt = $pdo->query($sql);
$grupos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Grupos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>
<h1>Listado de Grupos</h1>
<a href="crear.php">Agregar Grupo</a>
<table>
    <tr>
        <th>Nombre</th><th>Materia</th><th>Profesor</th><th>Horario</th><th>Acciones</th>
    </tr>
    <?php foreach ($grupos as $g): ?>
        <tr>
            <td><?= htmlspecialchars($g['nombre']) ?></td>
            <td><?= htmlspecialchars($g['materia']) ?></td>
            <td><?= htmlspecialchars($g['profesor']) ?></td>
            <td><?= htmlspecialchars($g['horario']) ?></td>
            <td>
                <a href="editar.php?id=<?= $g['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $g['id'] ?>" onclick="return confirmarEliminacion()">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script src="../../js/confirmaciones.js"></script>
</body>
</html>
