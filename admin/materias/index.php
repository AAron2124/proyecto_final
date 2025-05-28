<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM materias ORDER BY nombre");
$materias = $stmt->fetchAll();
?>

<h2 class="mb-4">Materias</h2>

<a href="crear.php" class="btn btn-primary mb-3">Agregar Materia</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($materias as $materia): ?>
        <tr>
            <td><?= $materia['id'] ?></td>
            <td><?= htmlspecialchars($materia['nombre']) ?></td>
            <td><?= htmlspecialchars($materia['descripcion']) ?></td>
            <td>
                <a href="editar.php?id=<?= $materia['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="eliminar.php?id=<?= $materia['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar esta materia?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require '../../includes/footer.php'; ?>
