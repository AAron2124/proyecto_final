<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// Obtener la calificación actual con datos relacionados para mostrar en el formulario
$sql = "SELECT c.id, c.calificacion, c.fecha, c.alumno_id, c.asignacion_id,
               a.nombre AS alumno_nombre, a.apellido AS alumno_apellido,
               m.nombre AS materia_nombre,
               g.nombre AS grupo_nombre,
               p.nombre AS profesor_nombre, p.apellido AS profesor_apellido
        FROM calificaciones c
        JOIN alumnos a ON c.alumno_id = a.id
        JOIN asignaciones asi ON c.asignacion_id = asi.id
        JOIN materias m ON asi.materia_id = m.id
        JOIN grupos g ON asi.grupo_id = g.id
        JOIN profesores p ON asi.profesor_id = p.id
        WHERE c.id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$calificacion = $stmt->fetch();

if (!$calificacion) {
    echo "<div class='alert alert-danger'>Calificación no encontrada.</div>";
    require '../../includes/footer.php';
    exit;
}

// Obtener lista de alumnos para el select
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY apellido, nombre")->fetchAll();

// Obtener lista de asignaciones para el select
$asignaciones = $pdo->query("
    SELECT asi.id, m.nombre AS materia, g.nombre AS grupo, p.nombre AS profesor_nombre, p.apellido AS profesor_apellido
    FROM asignaciones asi
    JOIN materias m ON asi.materia_id = m.id
    JOIN grupos g ON asi.grupo_id = g.id
    JOIN profesores p ON asi.profesor_id = p.id
    ORDER BY m.nombre, g.nombre
")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'];
    $asignacion_id = $_POST['asignacion_id'];
    $calificacion_val = $_POST['calificacion'];
    $fecha = $_POST['fecha'];

    // Validaciones básicas
    $errors = [];
    if (empty($alumno_id)) $errors[] = "Debe seleccionar un alumno.";
    if (empty($asignacion_id)) $errors[] = "Debe seleccionar una asignación.";
    if (!is_numeric($calificacion_val) || $calificacion_val < 0 || $calificacion_val > 100) {
        $errors[] = "La calificación debe ser un número entre 0 y 100.";
    }
    if (empty($fecha)) $errors[] = "Debe ingresar una fecha.";

    if (empty($errors)) {
        $sql_update = "UPDATE calificaciones SET alumno_id = :alumno_id, asignacion_id = :asignacion_id, calificacion = :calificacion, fecha = :fecha WHERE id = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            'alumno_id' => $alumno_id,
            'asignacion_id' => $asignacion_id,
            'calificacion' => $calificacion_val,
            'fecha' => $fecha,
            'id' => $id,
        ]);
        header("Location: index.php");
        exit;
    }
}
?>

<div class="container mt-4">
    <h2>Editar Calificación</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="alumno_id" class="form-label">Alumno</label>
            <select id="alumno_id" name="alumno_id" class="form-select" required>
                <option value="">-- Seleccionar alumno --</option>
                <?php foreach ($alumnos as $alumno): ?>
                    <option value="<?= $alumno['id'] ?>" <?= ($calificacion['alumno_id'] == $alumno['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($alumno['apellido'] . ", " . $alumno['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="asignacion_id" class="form-label">Asignación (Materia - Grupo - Profesor)</label>
            <select id="asignacion_id" name="asignacion_id" class="form-select" required>
                <option value="">-- Seleccionar asignación --</option>
                <?php foreach ($asignaciones as $asi): ?>
                    <option value="<?= $asi['id'] ?>" <?= ($calificacion['asignacion_id'] == $asi['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($asi['materia'] . " - " . $asi['grupo'] . " - " . $asi['profesor_nombre'] . " " . $asi['profesor_apellido']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="calificacion" class="form-label">Calificación</label>
            <input type="number" step="0.01" min="0" max="100" id="calificacion" name="calificacion" class="form-control" value="<?= htmlspecialchars($calificacion['calificacion']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" id="fecha" name="fecha" class="form-control" value="<?= htmlspecialchars($calificacion['fecha']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php
require '../../includes/footer.php';
?>
