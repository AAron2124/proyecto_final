<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo "<p>Usuario no encontrado.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $rol = $_POST['rol'];

    $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, rol = ? WHERE id = ?");
    $stmt->execute([$username, $rol, $id]);

    header("Location: usuarios.php");
    exit;
}
?>

<h2>Editar Usuario</h2>
<form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="username" class="form-label">Usuario:</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($usuario['username']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol:</label>
        <select name="rol" id="rol" class="form-select" required>
            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            <option value="alumno" <?= $usuario['rol'] == 'alumno' ? 'selected' : '' ?>>Alumno</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
