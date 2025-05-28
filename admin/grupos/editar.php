<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM grupos WHERE id = ?");
$stmt->execute([$id]);
$grupo = $stmt->fetch();

if (!$grupo) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $nivel = trim($_POST['nivel']);

    if (empty($nombre)) {
        $error = 'El nombre es obligatorio.';
    } elseif (empty($nivel)) {
        $error = 'El nivel es obligatorio.';
    } else {
        $stmt = $pdo->prepare("UPDATE grupos SET nombre = ?, nivel = ? WHERE id = ?");
        if ($stmt->execute([$nombre, $nivel, $id])) {
            header("Location: index.php");
            exit;
        } else {
            $error = 'Error al actualizar el grupo.';
        }
    }
} else {
    $nombre = $grupo['nombre'];
    $nivel = $grupo['nivel'];
}
?>

<h2 class="mb-4">Editar Grupo</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required>
    </div>
    <div class="mb-3">
        <label for="nivel" class="form-label">Nivel</label>
        <input type="text" name="nivel" id="nivel" class="form-control" value="<?= htmlspecialchars($nivel) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
