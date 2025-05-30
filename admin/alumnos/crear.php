<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if (empty($username) || empty($password) || empty($nombre) || empty($apellido)) {
        echo "<div class='alert alert-danger'>Nombre de usuario, contraseña, nombre y apellido son obligatorios.</div>";
    } else {
        // Verificar si username ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            echo "<div class='alert alert-danger'>El nombre de usuario ya existe. Elige otro.</div>";
        } else {
            // Insertar usuario con password plano
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, 'alumno')");
            $stmt->execute([$username, $password]);
            $usuario_id = $pdo->lastInsertId();

            // Insertar alumno
            $sql = "INSERT INTO alumnos (nombre, apellido, fecha_nacimiento, direccion, telefono, correo, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $direccion, $telefono, $correo, $usuario_id]);

            echo "<div class='alert alert-success'>Alumno y usuario creados correctamente.</div>";
        }
    }
}
?>

<h2 class="mb-4">Agregar Alumno</h2>

<form method="post" action="crear.php">
    <div class="mb-3">
        <label for="username" class="form-label">Nombre de Usuario *</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña *</label>
        <input type="text" class="form-control" id="password" name="password" value="<?= htmlspecialchars($_POST['password'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre *</label>
        <input type="text" class="form-control" id="nombre" name="nombre" 
            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" 
            required 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            title="Solo letras y espacios permitidos">
    </div>
    <div class="mb-3">
        <label for="apellido" class="form-label">Apellido *</label>
        <input type="text" class="form-control" id="apellido" name="apellido" 
            value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>" 
            required 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            title="Solo letras y espacios permitidos">
    </div>
    <div class="mb-3">
        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $_POST['fecha_nacimiento'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" 
            value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" 
            pattern="[0-9]{7,15}" 
            title="Solo números (entre 7 y 15 dígitos)">
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
    </div>

    <button type="submit" class="btn btn-success">Agregar Alumno</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php include '../../includes/footer.php'; ?>
