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
$grupo_id = '';
$profesor_id = '';
$errors = [];

$grupos = $pdo->query("SELECT id, nombre FROM grupos ORDER BY nombre")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre, apellido FROM profesores ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $grupo_id = $_POST['grupo_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';

    if (empty($nombre)) {
        $errors[] = "El nombre es obligatorio.";
    }
    if (empty($grupo_id)) {
        $errors[] = "Seleccione un grupo (carrera).";
    }
    if (empty($profesor_id)) {
        $errors[] = "Seleccione un profesor.";
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Insertar materia
            $stmt = $pdo->prepare("INSERT INTO materias (nombre, descripcion) VALUES (?, ?)");
            $stmt->execute([$nombre, $descripcion]);
            $materia_id = $pdo->lastInsertId();

            // Insertar asignación materia-grupo-profesor
            $stmt2 = $pdo->prepare("INSERT INTO asignaciones (materia_id, grupo_id, profesor_id) VALUES (?, ?, ?)");
            $stmt2->execute([$materia_id, $grupo_id, $profesor_id]);

            $pdo->commit();

            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error al guardar materia y asignación: " . $e->getMessage();
        }
    }
}
?>

<h2 class="mb-4">Agregar Materia con Grupo y Profesor</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Materia</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control"><?= htmlspecialchars($descripcion) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="grupo_id" class="form-label">Grupo (Carrera)</label>
        <select name="grupo_id" id="grupo_id" class="form-select" required>
            <option value="">-- Seleccionar grupo (carrera) --</option>
            <?php foreach ($grupos as $grupo): ?>
                <option value="<?= $grupo['id'] ?>" <?= ($grupo_id == $grupo['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($grupo['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor</label>
        <select name="profesor_id" id="profesor_id" class="form-select" required>
            <option value="">-- Seleccionar profesor --</option>
            <?php foreach ($profesores as $prof): ?>
                <option value="<?= $prof['id'] ?>" <?= ($profesor_id == $prof['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prof['nombre'] . ' ' . $prof['apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Materia</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
