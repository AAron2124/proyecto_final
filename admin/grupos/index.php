<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Obtener todas las carreras
$stmt = $pdo->query("SELECT * FROM grupos ORDER BY nombre");
$grupos = $stmt->fetchAll();
?>

<h2 class="mb-4">Carreras</h2>

<a href="crear.php" class="btn btn-primary mb-3">Crear Carrera</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Nivel</th>
            <th>Alumnos asignados</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grupos as $grupo): ?>
            <?php
                // Contar alumnos asignados
                $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM alumnos_grupos WHERE grupo_id = ?");
                $stmtCount->execute([$grupo['id']]);
                $alumnosCount = $stmtCount->fetchColumn();
            ?>
            <tr>
                <td><?= htmlspecialchars($grupo['id']) ?></td>
                <td><?= htmlspecialchars($grupo['nombre']) ?></td>
                <td><?= htmlspecialchars($grupo['nivel']) ?></td>
                <td><?= $alumnosCount ?></td>
                <td>
                    <a href="editar.php?id=<?= $grupo['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="gestionar_alumnos.php?id=<?= $grupo['id'] ?>" class="btn btn-sm btn-info">Gestionar Alumnos</a>
                    <a href="eliminar.php?id=<?= $grupo['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este grupo?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require '../../includes/footer.php'; ?>
