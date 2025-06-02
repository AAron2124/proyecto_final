<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

// Obtener lista de alumnos para el select
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY nombre")->fetchAll();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'] ?? '';
    $grupo_id = $_POST['grupo_id'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $profesor_id = $_POST['profesor_id'] ?? '';
    $calificacion = $_POST['calificacion'] ?? '';
    $fecha = $_POST['fecha'] ?? date('Y-m-d');

    if (!$alumno_id) $errors[] = "Seleccione un alumno.";
    if (!$grupo_id) $errors[] = "Seleccione un grupo.";
    if (!$materia_id) $errors[] = "Seleccione una materia.";
    if (!$profesor_id) $errors[] = "Seleccione un profesor.";
    if ($calificacion === '' || !is_numeric($calificacion) || $calificacion < 0 || $calificacion > 100) {
        $errors[] = "Ingrese una calificación válida entre 0 y 100.";
    }
    if (!$fecha) $errors[] = "Ingrese una fecha válida.";

    // Validar que el grupo pertenece al alumno
    if ($alumno_id && $grupo_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM alumnos_grupos WHERE alumno_id = ? AND grupo_id = ?");
        $stmt->execute([$alumno_id, $grupo_id]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "El grupo seleccionado no corresponde al alumno.";
        }
    }

    // Validar que el alumno no tenga ya calificación para esa materia y grupo
    if ($alumno_id && $grupo_id && $materia_id) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM calificaciones WHERE alumno_id = ? AND grupo_id = ? AND materia_id = ?");
        $stmt->execute([$alumno_id, $grupo_id, $materia_id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "El alumno ya tiene una calificación para esta materia en este grupo.";
        }
    }

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

<form method="post" id="formCalificaciones">
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
        <select name="grupo_id" id="grupo_id" class="form-select" required disabled>
            <option value="">-- Seleccione primero un alumno --</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="materia_id" class="form-label">Materia</label>
        <select name="materia_id" id="materia_id" class="form-select" required disabled>
            <option value="">-- Seleccionar materia --</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="profesor_id" class="form-label">Profesor</label>
        <select name="profesor_id" id="profesor_id" class="form-select" required disabled>
            <option value="">-- Seleccionar profesor --</option>
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

<script>
// Cargar grupos según alumno seleccionado
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
        })
        .catch(() => {
            grupoSelect.innerHTML = '<option value="">Error al cargar grupos</option>';
            grupoSelect.disabled = true;
        });
});

// Cargar materias según grupo y alumno seleccionados
document.getElementById('grupo_id').addEventListener('change', function () {
    const grupoId = this.value;
    const alumnoId = document.getElementById('alumno_id').value;
    const materiaSelect = document.getElementById('materia_id');
    const profesorSelect = document.getElementById('profesor_id');

    materiaSelect.innerHTML = '<option value="">Cargando materias...</option>';
    materiaSelect.disabled = true;
    profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
    profesorSelect.disabled = true;

    if (!grupoId || !alumnoId) {
        materiaSelect.innerHTML = '<option value="">-- Seleccionar materia --</option>';
        materiaSelect.disabled = true;
        return;
    }

    fetch('obtener_materias.php?grupo_id=' + grupoId + '&alumno_id=' + alumnoId)
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
        })
        .catch(() => {
            materiaSelect.innerHTML = '<option value="">Error al cargar materias</option>';
            materiaSelect.disabled = true;
        });
});

// Cargar profesores según materia y grupo seleccionados
document.getElementById('materia_id').addEventListener('change', function () {
    const grupoId = document.getElementById('grupo_id').value;
    const materiaId = this.value;
    const profesorSelect = document.getElementById('profesor_id');

    profesorSelect.innerHTML = '<option value="">Cargando profesores...</option>';
    profesorSelect.disabled = true;

    if (!grupoId || !materiaId) {
        profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
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
window.addEventListener('DOMContentLoaded', () => {
    const alumnoId = document.getElementById('alumno_id').value;
    const grupoId = '<?= isset($grupo_id) ? (int)$grupo_id : '' ?>';
    const materiaId = '<?= isset($materia_id) ? (int)$materia_id : '' ?>';
    const profesorId = '<?= isset($profesor_id) ? (int)$profesor_id : '' ?>';

    if (alumnoId) {
        // Cargar grupos
        fetch('obtener_grupos.php?alumno_id=' + alumnoId)
            .then(res => res.json())
            .then(data => {
                const grupoSelect = document.getElementById('grupo_id');
                grupoSelect.innerHTML = '<option value="">-- Seleccionar grupo --</option>';
                data.forEach(g => {
                    const option = document.createElement('option');
                    option.value = g.id;
                    option.textContent = g.nombre;
                    if (g.id == grupoId) option.selected = true;
                    grupoSelect.appendChild(option);
                });
                grupoSelect.disabled = false;

                if (grupoId) {
                    // Cargar materias
                    fetch('obtener_materias.php?grupo_id=' + grupoId + '&alumno_id=' + alumnoId)
                        .then(res => res.json())
                        .then(data => {
                            const materiaSelect = document.getElementById('materia_id');
                            materiaSelect.innerHTML = '<option value="">-- Seleccionar materia --</option>';
                            data.forEach(m => {
                                const option = document.createElement('option');
                                option.value = m.id;
                                option.textContent = m.nombre;
                                if (m.id == materiaId) option.selected = true;
                                materiaSelect.appendChild(option);
                            });
                            materiaSelect.disabled = false;

                            if (materiaId) {
                                // Cargar profesores
                                fetch(`obtener_profesores.php?grupo_id=${grupoId}&materia_id=${materiaId}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        const profesorSelect = document.getElementById('profesor_id');
                                        profesorSelect.innerHTML = '<option value="">-- Seleccionar profesor --</option>';
                                        data.forEach(p => {
                                            const option = document.createElement('option');
                                            option.value = p.id;
                                            option.textContent = p.nombre + ' ' + p.apellido;
                                            if (p.id == profesorId) option.selected = true;
                                            profesorSelect.appendChild(option);
                                        });
                                        profesorSelect.disabled = false;
                                    });
                            }
                        });
                }
            });
    }
});

</script>

<?php require '../../includes/footer.php'; ?>
