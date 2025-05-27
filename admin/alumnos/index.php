<?php
include '../../includes/db.php';
include '../../includes/funciones.php';
//protegerAdmin();

$stmt = $pdo->query("SELECT a.*, u.username FROM alumnos a LEFT JOIN usuarios u ON a.usuario_id = u.id");
$alumnos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Alumnos</h2>
        <a href="crear.php" class="btn btn-success">Agregar Alumno</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= $alumno['id'] ?></td>
                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                    <td><?= htmlspecialchars($alumno['apellido']) ?></td>
                    <td><?= htmlspecialchars($alumno['correo']) ?></td>
                    <td><?= htmlspecialchars($alumno['telefono']) ?></td>
                    <td><?= htmlspecialchars($alumno['username']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $alumno['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="eliminar.php?id=<?= $alumno['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
