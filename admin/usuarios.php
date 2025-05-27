<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

//if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
//    header("Location: ../views/login.php");
  //  exit;
//}

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<h2 class="mb-4">Gestión de Usuarios 👤</h2>

<a href="crear_usuario.php" class="btn btn-primary mb-3">Agregar Usuario</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['username']) ?></td>
            <td><?= $usuario['rol'] ?></td>
            <td>
                <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require '../includes/footer.php'; ?>
