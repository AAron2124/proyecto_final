<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM profesores ORDER BY id ASC");
$profesores = $stmt->fetchAll();
?>

<h2 class="mb-4">Lista de Profesores</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Profesor</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Carrera/s</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($profesores as $profesor): ?>
        <tr>
            <td><?= htmlspecialchars($profesor['id']) ?></td>
            <td><?= htmlspecialchars($profesor['nombre']) ?></td>
            <td><?= htmlspecialchars($profesor['apellido']) ?></td>
            <td><?= htmlspecialchars($profesor['especialidad']) ?></td>
            <td><?= htmlspecialchars($profesor['telefono']) ?></td>
            <td><?= htmlspecialchars($profesor['correo']) ?></td>
            <td>
                <a href="editar.php?id=<?= $profesor['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="eliminar.php?id=<?= $profesor['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar profesor?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require '../../includes/footer.php'; ?>
