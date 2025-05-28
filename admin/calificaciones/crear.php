<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Obtener listas para los selects
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY apellido, nombre")->fetchAll();
$materias = $pdo->query("SELECT id, nombre FROM materias ORDER BY nombre")->fetchAll();
$grupos = $pdo->query("SELECT id, nombre FROM grupos ORDER BY nombre")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre FROM profesores ORDER BY nombre")->fetchAll();

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'] ?? null;
    $materia_id = $_POST['materia_id'] ?? null;
    $grupo_id = $_POST['grupo_id'] ?? null;
    $profesor_id = $_POST['profesor_id'] ?? null;
    $calificacion = $_POST['calificacion'] ?? null;

    // Validaciones básicas
    if (!$alumno_id) $errores[] = "Debe seleccionar un alumno.";
    if (!$materia_id) $errores[] = "Debe seleccionar una materia.";
    if (!$grupo_id) $errores[] = "Debe seleccionar un grupo.";
    if (!$profesor_id) $errores[] = "Debe seleccionar un profesor.";
    if ($calificacion === null || $calificacion === '') {
        $errores[] = "Debe ingresar una calificación.";
    } elseif (!is_numeric($calificacion) || $calificacion < 0 || $calificacion > 100) {
        $errores[] = "La calificación debe ser un número entre 0 y 100.";
    }

    if (empty($errores)) {
        // Insertar calificación
        $stmt = $pdo->prepare("INSERT INTO calificaciones (alumno_id, materia_id, grupo_id, profesor_id, calificacion, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$alumno_id, $materia_id, $grupo_id, $profesor_id, $calificacion]);
        header("Location: index.php");
        exit;
    }
}
?>

<div class="container mt-4">
    <h2>Agregar Calificación</h2>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="create.php">
        <div class="mb-3">
            <label for="alumno_id" class="form-label">Alumno</label>
            <select class="form-select" name="alumno_id" id="alumno_id" required>
                <option value="">-- Seleccione un alumno --</option>
                <?php foreach ($alumnos as $alumno): ?>
                    <option value="<?= $alumno['id'] ?>" <?= (isset($alumno_id) && $alumno_id == $alumno['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($alumno['apellido'] . ', ' . $alumno['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="materia_id" class="form-label">Materia</label>
            <select class="form-select" name="materia_id" id="materia_id" required>
                <option value="">-- Seleccione una materia --</option>
                <?php foreach ($materias as $materia): ?>
                    <option value="<?= $materia['id'] ?>" <?= (isset($materia_id) && $materia_id == $materia['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($materia['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="grupo_id" class="form-label">Grupo</label>
            <select class="form-select" name="grupo_id" id="grupo_id" required>
                <option value="">-- Seleccione un grupo --</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>" <?= (isset($grupo_id) && $grupo_id == $grupo['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($grupo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="profesor_id" class="form-label">Profesor</label>
            <select class="form-select" name="profesor_id" id="profesor_id" required>
                <option value="">-- Seleccione un profesor --</option>
                <?php foreach ($profesores as $profesor): ?>
                    <option value="<?= $profesor['id'] ?>" <?= (isset($profesor_id) && $profesor_id == $profesor['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($profesor['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="calificacion" class="form-label">Calificación (0-100)</label>
            <input type="number" step="0.01" min="0" max="100" class="form-control" id="calificacion" name="calificacion" value="<?= htmlspecialchars($calificacion ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require '../../includes/footer.php'; ?>
