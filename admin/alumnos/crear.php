<?php
include '../../includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena']; // Texto plano, sin hash

    try {
        // 1. Insertar el usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, 'alumno')");
        $stmt->execute([$usuario, $contrasena]);
        $usuario_id = $pdo->lastInsertId();

        // 2. Insertar el alumno con la FK
        $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellido, fecha_nacimiento, direccion, telefono, correo, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $fecha, $direccion, $telefono, $correo, $usuario_id]);

        $mensaje = "Alumno registrado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al guardar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../../includes/header.php'; ?>
<div class="container mt-5">
    <h2 class="mb-4">Nuevo Alumno</h2>

    <?php if ($mensaje): ?>
        <div class="alert <?= str_contains($mensaje, 'Error') ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Apellido</label>
            <input name="apellido" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Fecha de nacimiento</label>
            <input name="fecha_nacimiento" type="date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Dirección</label>
            <input name="direccion" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Teléfono</label>
            <input name="telefono" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input name="correo" type="email" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Usuario</label>
            <input name="usuario" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contraseña</label>
            <input name="contrasena" type="text" class="form-control" required>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Guardar Alumno</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
<?php include '../../includes/footer.php'; ?>
</body>
</html>
