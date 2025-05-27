<?php
include '../../includes/db.php';

$stmt = $pdo->query("SELECT * FROM profesores");
$profesores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Profesores</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>
<h1>Listado de Profesores</h1>
<a href="crear.php">Agregar Profesor</a>
<table>
    <tr>
        <th>Nombre</th><th>Apellido</th><th>Especialidad</th><th>Correo</th><th>Acciones</th>
    </tr>
    <?php foreach ($profesores as $prof): ?>
        <tr>
            <td><?= htmlspecialchars($prof['nombre']) ?></td>
            <td><?= htmlspecialchars($prof['apellido']) ?></td>
            <td><?= htmlspecialchars($prof['especialidad']) ?></td>
            <td><?= htmlspecialchars($prof['correo']) ?></td>
            <td>
                <a href="editar.php?id=<?= $prof['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $prof['id'] ?>" onclick="return confirmarEliminacion()">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script src="../../js/confirmaciones.js"></script>
</body>
</html>
