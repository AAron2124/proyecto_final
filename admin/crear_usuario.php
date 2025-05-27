<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $rol]);

    header("Location: usuarios.php");
    exit;
}
?>

<h2>Agregar Usuario</h2>
<form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="username" class="form-label">Nombre de usuario:</label>
        <input type="text" name="username" id="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" name="password" id="password" class="form-control" required minlength="6">
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol:</label>
        <select name="rol" id="rol" class="form-select" required>
            <option value="">-- Seleccionar --</option>
            <option value="admin">Administrador</option>
            <option value="alumno">Alumno</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../includes/footer.php'; ?>
