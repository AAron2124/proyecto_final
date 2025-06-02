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

// Obtener calificación actual
$stmt = $pdo->prepare("SELECT * FROM calificaciones WHERE id = ?");
$stmt->execute([$id]);
$calificacionData = $stmt->fetch();

if (!$calificacionData) {
    header("Location: index.php");
    exit;
}

// Obtener lista de alumnos
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY nombre")->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'] ?? '';
    $grupo_id = $_POST['grupo_id'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';
    $calificacion = $_POST['calificacion'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    // Validaciones básicas
    if (!$alumno_id) $errors[] = "Seleccione un alumno.";
    if (!$grupo_id) $errors[] = "Seleccione un grupo.";
    if (!$materia_id) $errors[] = "Seleccione una materia.";
    if (!$profesor_id) $errors[] = "Seleccione un profesor.";
    if ($calificacion === '' || !is_numeric($calificacion) || $calificacion < 0 || $calificacion > 100) {
        $errors[] = "Ingrese una calificación válida entre 0 y 100.";
    }
    if (!$fecha) $errors[] = "Ingrese una fecha válida.";

    // Validación: evitar duplicados de calificación para el mismo alumno y materia (excluyendo esta)
    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("
            SELECT COUNT(*) FROM calificaciones
            WHERE alumno_id = ? AND materia_id = ? AND id != ?
        ");
        $stmtCheck->execute([$alumno_id, $materia_id, $id]);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            $errors[] = "El alumno ya tiene una calificación registrada para esta materia.";
        }
    }

    // Si no hay errores, actualizar
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE calificaciones SET alumno_id = ?, grupo_id = ?, materia_id = ?, profesor_id = ?, calificacion = ?, fecha = ? WHERE id = ?");
        $stmt->execute([$alumno_id, $grupo_id, $materia_id, $profesor_id, $calificacion, $fecha, $id]);
        header("Location: index.php");
        exit;
    }
} else {
    // Cargar valores actuales para el formulario
    $alumno_id = $calificacionData['alumno_id'];
    $grupo_id = $calificacionData['grupo_id'];
    $materia_id = $calificacionData['materia_id'];
    $profesor_id = $calificacionData['profesor_id'];
    $calificacion = $calificacionData['calificacion'];
    $fecha = $calificacionData['fecha'];
}

// Cargar grupos del alumno actual para cargar el select grupos
$stmt = $pdo->prepare("SELECT g.id, g.nombre FROM grupos g JOIN alumnos_grupos ag ON g.id = ag.grupo_id WHERE ag.alumno_id = ? ORDER BY g.nombre");
$stmt->execute([$alumno_id]);
$grupos = $stmt->fetchAll();

// Cargar materias del grupo actual para cargar select materias
$stmt = $pdo->prepare("SELECT DISTINCT m.id, m.nombre FROM asignaciones a JOIN materias m ON a.materia_id = m.id WHERE a.grupo_id = ? ORDER BY m.nombre");
$stmt->execute([$grupo_id]);
$materias = $stmt->fetchAll();

// Cargar profesores de materia y grupo actual para select profesores
$stmt = $pdo->prepare("SELECT p.id, p.nombre, p.apellido FROM asignaciones a JOIN profesores p ON a.profesor_id = p.id WHERE a.grupo_id = ? AND a.materia_id = ? ORDER BY p.nombre");
$stmt->execute([$grupo_id, $materia_id]);
$profesores = $stmt->fetchAll();
?>

<h2>Editar Calificación</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" id="formCalificaciones">
    <div class="mb-3">
        <label for="alumno_id" class="form-label">Alumno</label>
        <select name="alumno_id" id="alumno_id" class="form-select" required>
            <option value="">-- Seleccionar alumno --</option>
            <?php foreach ($alumnos as $al): ?>
                <option value="<?= $al['id'] ?>" <?= ($alumno_id == $al['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($al['nombre'] . ' ' . $al['apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="grupo_id" class="form-label">Grupo</label>
        <select name="grupo_id" id="grupo_id" class="form-select" required <?= empty($grupos) ? 'disabled' : '' ?>>
            <option value="">-- Seleccionar grupo --</option>
            <?php foreach ($grupos as $gr): ?>
                <option value="<?= $gr['id'] ?>" <?= ($grupo_id == $gr['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($gr['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="materia_id" class="form-label">Materia</label>
        <select name="materia_id" id="materia_id" class="form-select" required <?= empty($materias) ? 'disabled' : '' ?>>
            <option value="">-- Seleccionar materia --</option>
            <?php foreach ($materias as $mat): ?>
                <option value="<?= $mat['id'] ?>" <?= ($materia_id == $mat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor</label>
        <select name="profesor_id" id="profesor_id" class="form-select" required <?= empty($profesores) ? 'disabled' : '' ?>>
            <option value="">-- Seleccionar profesor --</option>
            <?php foreach ($profesores as $prof): ?>
                <option value="<?= $prof['id'] ?>" <?= ($profesor_id == $prof['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prof['nombre'] . ' ' . $prof['apellido']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="calificacion" class="form-label">Calificación (0-100)</label>
        <input type="number" name="calificacion" id="calificacion" min="0" max="100" step="0.01" value="<?= htmlspecialchars($calificacion) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($fecha) ?>" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<script>
// Script para actualizar selects dependientes al cambiar alumno, grupo o materia (igual que antes)
document.getElementById('alumno_id').addEventListener('change', function () {
    const alumnoId = this.value;
    const grupoSelect = document.getElementById('grupo_id');
    const materiaSelect = document.getElementById('materia_id');
    const profesorSelect = document.getElementById('profesor_id');

    grupoSelect.innerHTML = '<option value="">Cargando grupos...</option>';
    grupoSelect.disabled = true;
    materiaSelect.innerHTML = '<option value="">-- Seleccionar materia --</option>';
    materiaSelect.disabled = true;
    profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
    profesorSelect.disabled = true;

    if (!alumnoId) {
        grupoSelect.innerHTML = '<option value="">-- Seleccione primero un alumno --</option>';
        grupoSelect.disabled = true;
        return;
    }

    fetch('obtener_grupos.php?alumno_id=' + alumnoId)
        .then(response => response.json())
        .then(data => {
            grupoSelect.innerHTML = '<option value="">-- Seleccionar grupo --</option>';
            data.forEach(grupo => {
                const option = document.createElement('option');
                option.value = grupo.id;
                option.textContent = grupo.nombre;
                grupoSelect.appendChild(option);
            });
            grupoSelect.disabled = false;

            materiaSelect.innerHTML = '<option value="">-- Seleccionar materia --</option>';
            materiaSelect.disabled = true;
            profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
            profesorSelect.disabled = true;
        })
        .catch(() => {
            grupoSelect.innerHTML = '<option value="">Error al cargar grupos</option>';
            grupoSelect.disabled = true;
        });
});

document.getElementById('grupo_id').addEventListener('change', function () {
    const grupoId = this.value;
    const materiaSelect = document.getElementById('materia_id');
    const profesorSelect = document.getElementById('profesor_id');

    materiaSelect.innerHTML = '<option value="">Cargando materias...</option>';
    materiaSelect.disabled = true;
    profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
    profesorSelect.disabled = true;

    if (!grupoId) {
        materiaSelect.innerHTML = '<option value="">-- Seleccione primero un grupo --</option>';
        materiaSelect.disabled = true;
        return;
    }

    fetch('obtener_materias.php?grupo_id=' + grupoId)
        .then(response => response.json())
        .then(data => {
            materiaSelect.innerHTML = '<option value="">-- Seleccionar materia --</option>';
            data.forEach(materia => {
                const option = document.createElement('option');
                option.value = materia.id;
                option.textContent = materia.nombre;
                materiaSelect.appendChild(option);
            });
            materiaSelect.disabled = false;

            profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
            profesorSelect.disabled = true;
        })
        .catch(() => {
            materiaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
            materiaSelect.disabled = true;
        });
});

document.getElementById('materia_id').addEventListener('change', function () {
    const grupoId = document.getElementById('grupo_id').value;
    const materiaId = this.value;
    const profesorSelect = document.getElementById('profesor_id');

    profesorSelect.innerHTML = '<option value="">Cargando profesores...</option>';
    profesorSelect.disabled = true;

    if (!materiaId || !grupoId) {
        profesorSelect.innerHTML = '<option value="">-- Seleccione primero grupo y materia --</option>';
        profesorSelect.disabled = true;
        return;
    }

    fetch(`obtener_profesores.php?grupo_id=${grupoId}&materia_id=${materiaId}`)
        .then(response => response.json())
        .then(data => {
            profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
            data.forEach(profesor => {
                const option = document.createElement('option');
                option.value = profesor.id;
                option.textContent = profesor.nombre + ' ' + profesor.apellido;
                profesorSelect.appendChild(option);
            });
            profesorSelect.disabled = false;
        })
        .catch(() => {
            profesorSelect.innerHTML = '<option value="">Error al cargar profesores</option>';
            profesorSelect.disabled = true;
        });
});
</script>

<?php require '../../includes/footer.php'; ?>
