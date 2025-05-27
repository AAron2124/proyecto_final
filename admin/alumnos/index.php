<?php
include '../../includes/db.php';

$stmt = $pdo->query("SELECT * FROM alumnos");
$alumnos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>
<h1>Listado de Alumnos</h1>
<a href="crear.php">Agregar Alumno</a>
<table>
    <tr>
        <th>Nombre</th><th>Apellido</th><th>Correo</th><th>Acciones</th>
    </tr>
    <?php foreach ($alumnos as $al): ?>
        <tr>
            <td><?= htmlspecialchars($al['nombre']) ?></td>
            <td><?= htmlspecialchars($al['apellido']) ?></td>
            <td><?= htmlspecialchars($al['correo']) ?></td>
            <td>
                <a href="editar.php?id=<?= $al['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $al['id'] ?>" onclick="return confirmarEliminacion()">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script src="../../js/confirmaciones.js"></script>
</body>
</html>
