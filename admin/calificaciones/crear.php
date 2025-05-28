<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Obtener listas para selects
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY nombre")->fetchAll();
$grupos = $pdo->query("SELECT id, nombre FROM grupos ORDER BY nombre")->fetchAll();
$materias = $pdo->query("SELECT id, nombre FROM materias ORDER BY nombre")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre, apellido FROM profesores ORDER BY nombre")->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'] ?? '';
    $grupo_id = $_POST['grupo_id'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';
    $calificacion = $_POST['calificacion'] ?? '';
    $fecha = $_POST['fecha'] ?? date('Y-m-d');

    // Validar
    if (!$alumno_id) $errors[] = "Seleccione un alumno.";
    if (!$grupo_id) $errors[] = "Seleccione un grupo.";
    if (!$materia_id) $errors[] = "Seleccione una materia.";
    if (!$profesor_id) $errors[] = "Seleccione un profesor.";
    if ($calificacion === '' || !is_numeric($calificacion) || $calificacion < 0 || $calificacion > 100) {
        $errors[] = "Ingrese una calificación válida entre 0 y 100.";
    }
    if (!$fecha) $errors[] = "Ingrese una fecha válida.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO calificaciones (alumno_id, grupo_id, materia_id, profesor_id, calificacion, fecha) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$alumno_id, $grupo_id, $materia_id, $profesor_id, $calificacion, $fecha]);
        header("Location: index.php");
        exit;
    }
}
?>

<h2>Agregar Calificación</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="alumno_id" class="form-label">Alumno</label>
        <select name="alumno_id" id="alumno_id" class="form-select" required>
            <option value="">-- Seleccionar alumno --</option>
            <?php foreach ($alumnos as $al): ?>
                <option value="<?= $al['id'] ?>" <?= (isset($alumno_id) && $alumno_id == $al['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($al['nombre'] . ' ' . $al['apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="grupo_id" class="form-label">Grupo</label>
        <select name="grupo_id" id="grupo_id" class="form-select" required>
            <option value="">-- Seleccionar grupo --</option>
            <?php foreach ($grupos as $gr): ?>
                <option value="<?= $gr['id'] ?>" <?= (isset($grupo_id) && $grupo_id == $gr['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($gr['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="materia_id" class="form-label">Materia</label>
        <select name="materia_id" id="materia_id" class="form-select" required>
            <option value="">-- Seleccionar materia --</option>
            <?php foreach ($materias as $mat): ?>
                <option value="<?= $mat['id'] ?>" <?= (isset($materia_id) && $materia_id == $mat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor</label>
        <select name="profesor_id" id="profesor_id" class="form-select" required>
            <option value="">-- Seleccionar profesor --</option>
            <?php foreach ($profesores as $prof): ?>
                <option value="<?= $prof['id'] ?>" <?= (isset($profesor_id) && $profesor_id == $prof['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prof['nombre'] . ' ' . $prof['apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="calificacion" class="form-label">Calificación (0-100)</label>
        <input type="number" name="calificacion" id="calificacion" min="0" max="100" step="0.01" value="<?= htmlspecialchars($calificacion ?? '') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>
