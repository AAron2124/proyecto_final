<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$nombre = '';
$descripcion = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if (empty($nombre)) {
        $error = 'El nombre es obligatorio.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO materias (nombre, descripcion) VALUES (?, ?)");
        if ($stmt->execute([$nombre, $descripcion])) {
            header("Location: index.php");
            exit;
        } else {
            $error = 'Error al guardar la materia.';
        }
    }
}
?>

<h2 class="mb-4">Agregar Materia</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control"><?= htmlspecialchars($descripcion) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
