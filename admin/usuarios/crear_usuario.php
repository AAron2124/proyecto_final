<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = 'admin'; // Forzar el rol a 'admin' sin importar el valor enviado

    // Insertar solo si el rol es 'admin'
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $rol]);

    header("Location: usuarios.php");
    exit;
}
?>

<h2>Agregar Administrador</h2>
<form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="username" class="form-label">Nombre de usuario:</label>
        <input type="text" name="username" id="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" name="password" id="password" class="form-control" required minlength="6">
    </div>
    <!-- Campo oculto para el rol -->
    <input type="hidden" name="rol" value="admin">

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
