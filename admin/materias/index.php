<?php
include '../../includes/db.php';

$stmt = $pdo->query("SELECT * FROM materias");
$materias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Materias</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>
<h1>Listado de Materias</h1>
<a href="crear.php">Agregar Materia</a>
<table>
    <tr>
        <th>Nombre</th><th>Clave</th><th>Créditos</th><th>Semestre</th><th>Acciones</th>
    </tr>
    <?php foreach ($materias as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['nombre']) ?></td>
            <td><?= htmlspecialchars($m['clave']) ?></td>
            <td><?= $m['creditos'] ?></td>
            <td><?= $m['semestre'] ?></td>
            <td>
                <a href="editar.php?id=<?= $m['id'] ?>">Editar</a> |
                <a href="eliminar.php?id=<?= $m['id'] ?>" onclick="return confirmarEliminacion()">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script src="../../js/confirmaciones.js"></script>
</body>
</html>
