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

// Obtener materia
$stmt = $pdo->prepare("SELECT * FROM materias WHERE id = ?");
$stmt->execute([$id]);
$materia = $stmt->fetch();

if (!$materia) {
    header("Location: index.php");
    exit;
}

// Obtener asignación actual (grupo y profesor)
$stmt = $pdo->prepare("SELECT * FROM asignaciones WHERE materia_id = ?");
$stmt->execute([$id]);
$asignacion = $stmt->fetch();

if (!$asignacion) {
    // Si no hay asignación aún, se puede decidir si crearla al guardar o mostrar error
    $asignacion = ['grupo_id' => '', 'profesor_id' => '', 'id' => null];
}

// Obtener grupos y profesores para los selects
$grupos = $pdo->query("SELECT id, nombre FROM grupos ORDER BY nombre")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre, apellido FROM profesores ORDER BY nombre")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $grupo_id = $_POST['grupo_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';

    if (empty($nombre)) {
        $error = 'El nombre es obligatorio.';
    } elseif (empty($grupo_id)) {
        $error = 'Seleccione un grupo (carrera).';
    } elseif (empty($profesor_id)) {
        $error = 'Seleccione un profesor.';
    } else {
        try {
            $pdo->beginTransaction();

            // Actualizar materia
            $stmt = $pdo->prepare("UPDATE materias SET nombre = ?, descripcion = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $id]);

            if ($asignacion['id']) {
                // Actualizar asignación existente
                $stmt2 = $pdo->prepare("UPDATE asignaciones SET grupo_id = ?, profesor_id = ? WHERE id = ?");
                $stmt2->execute([$grupo_id, $profesor_id, $asignacion['id']]);
            } else {
                // Insertar asignación nueva si no existía
                $stmt2 = $pdo->prepare("INSERT INTO asignaciones (materia_id, grupo_id, profesor_id) VALUES (?, ?, ?)");
                $stmt2->execute([$id, $grupo_id, $profesor_id]);
            }

            $pdo->commit();

            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Error al actualizar la materia y asignación: " . $e->getMessage();
        }
    }
} else {
    $nombre = $materia['nombre'];
    $descripcion = $materia['descripcion'];
    $grupo_id = $asignacion['grupo_id'];
    $profesor_id = $asignacion['profesor_id'];
}
?>

<h2 class="mb-4">Editar Materia</h2>

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

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
