<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';
include '../../includes/header.php';
?>

<h2 class="mb-4">Lista de Alumnos</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Alumno</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM alumnos");
        $hasRows = false;

        while ($row = $stmt->fetch()) {
            $hasRows = true;
    ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['apellido'] ?></td>
                <td><?= $row['correo'] ?></td>
                <td>
                    <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="eliminar.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar alumno?')">Eliminar</a>
                </td>
            </tr>
    <?php
        }

        if (!$hasRows) {
            echo "<tr><td colspan='5'>No hay alumnos registrados.</td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr><td colspan='5'>Error en la consulta: " . $e->getMessage() . "</td></tr>";
    }
    ?>
    </tbody>
</table>

<?php include '../../includes/footer.php'; ?>
