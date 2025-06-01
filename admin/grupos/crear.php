<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Obtener materias y profesores para los selects
$materias = $pdo->query("SELECT id, nombre FROM materias")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre FROM profesores")->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $nivel = trim($_POST['nivel'] ?? '');
    $materia_id = $_POST['materia_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';

    // Validar campos 
    if ($nombre === '') {
        $errors[] = "El nombre es obligatorio.";
    }
    if ($nivel === '') {
        $errors[] = "El nivel es obligatorio.";
    }
    if (!is_numeric($materia_id)) {
        $errors[] = "Debe seleccionar una materia válida.";
    }
    if (!is_numeric($profesor_id)) {
        $errors[] = "Debe seleccionar un profesor válido.";
    }

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO grupos (nombre, nivel, materia_id, profesor_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $nivel, $materia_id, $profesor_id]);
        header("Location: index.php");
        exit;
    }
}
?>

<h2 class="mb-4">Agregar Carrera</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="crear.php">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Carrera</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="nivel" class="form-label">Nivel</label>
        <input type="text" name="nivel" id="nivel" class="form-control" value="<?= htmlspecialchars($_POST['nivel'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="materia_id" class="form-label">Materia</label>
        <select name="materia_id" id="materia_id" class="form-select" required>
            <option value="">Seleccione una materia</option>
            <?php foreach ($materias as $materia): ?>
                <option value="<?= $materia['id'] ?>" <?= (isset($_POST['materia_id']) && $_POST['materia_id'] == $materia['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($materia['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor</label>
        <select name="profesor_id" id="profesor_id" class="form-select" required>
            <option value="">Seleccione un profesor</option>
            <?php foreach ($profesores as $profesor): ?>
                <option value="<?= $profesor['id'] ?>" <?= (isset($_POST['profesor_id']) && $_POST['profesor_id'] == $profesor['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($profesor['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
