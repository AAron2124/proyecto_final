<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$errors = [];
$nombre = '';
$apellido = '';
$especialidad = '';
$telefono = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $especialidad = trim($_POST['especialidad']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);

    // Validaciones básicas
    if (!$nombre) $errors[] = "El nombre es obligatorio.";
    if (!$apellido) $errors[] = "El apellido es obligatorio.";
    if (!$especialidad) $errors[] = "La especialidad es obligatoria.";
    if (!$telefono) $errors[] = "El teléfono es obligatorio.";
    if (!$correo) $errors[] = "El correo es obligatorio.";
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errors[] = "El correo no es válido.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO profesores (nombre, apellido, especialidad, telefono, correo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $especialidad, $telefono, $correo]);
        header("Location: index.php");
        exit;
    }
}
?>

<h2 class="mb-4">Agregar Profesor</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="crear.php" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required>
    </div>
    <div class="mb-3">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" name="apellido" id="apellido" class="form-control" value="<?= htmlspecialchars($apellido) ?>" required>
    </div>
    <div class="mb-3">
        <label for="especialidad" class="form-label">Especialidad</label>
        <input type="text" name="especialidad" id="especialidad" class="form-control" value="<?= htmlspecialchars($especialidad) ?>" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($telefono) ?>" required>
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($correo) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
