<?php
session_start();
require '../../includes/db.php';
require '../../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$grupo_id = $_GET['id'] ?? null;
if (!$grupo_id) {
    header("Location: index.php");
    exit;
}

// Obtener info del grupo
$stmt = $pdo->prepare("SELECT * FROM grupos WHERE id = ?");
$stmt->execute([$grupo_id]);
$grupo = $stmt->fetch();

if (!$grupo) {
    header("Location: index.php");
    exit;
}

// Procesar asignaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumnosSeleccionados = $_POST['alumnos'] ?? [];

    // Primero eliminar todas las relaciones actuales para este grupo
    $stmt = $pdo->prepare("DELETE FROM alumnos_grupos WHERE grupo_id = ?");
    $stmt->execute([$grupo_id]);

    // Insertar nuevas relaciones
    $stmtInsert = $pdo->prepare("INSERT INTO alumnos_grupos (alumno_id, grupo_id) VALUES (?, ?)");

    foreach ($alumnosSeleccionados as $alumno_id) {
        // Verificar si el alumno ya está en otro grupo
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM alumnos_grupos WHERE alumno_id = ? AND grupo_id != ?");
        $stmtCheck->execute([$alumno_id, $grupo_id]);
        $count = $stmtCheck->fetchColumn();
        
        if ($count == 0) {
            $stmtInsert->execute([$alumno_id, $grupo_id]);
        }
    }

    header("Location: index.php");
    exit;
}

// Obtener todos los alumnos
$stmt = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY nombre, apellido");
$alumnos = $stmt->fetchAll();

// Obtener alumnos asignados actualmente a ESTE grupo
$stmt = $pdo->prepare("SELECT alumno_id FROM alumnos_grupos WHERE grupo_id = ?");
$stmt->execute([$grupo_id]);
$alumnosAsignados = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Obtener alumnos asignados a OTROS grupos
$stmt = $pdo->prepare("SELECT DISTINCT alumno_id FROM alumnos_grupos WHERE grupo_id != ?");
$stmt->execute([$grupo_id]);
$alumnosEnOtrosGrupos = $stmt->fetchAll(PDO::FETCH_COLUMN);

?>

<h2 class="mb-4">Gestionar Alumnos en la Carrera: <?= htmlspecialchars($grupo['nombre']) ?></h2>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Seleccione los alumnos para asignar al grupo</label>
        <?php foreach ($alumnos as $alumno): 
            $estaEnOtroGrupo = in_array($alumno['id'], $alumnosEnOtrosGrupos);
            $estaEnEsteGrupo = in_array($alumno['id'], $alumnosAsignados);
        ?>
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="alumnos[]"
                    value="<?= $alumno['id'] ?>"
                    id="alumno_<?= $alumno['id'] ?>"
                    <?= $estaEnEsteGrupo ? 'checked' : '' ?>
                    <?= $estaEnOtroGrupo && !$estaEnEsteGrupo ? 'disabled' : '' ?>
                >
                <label class="form-check-label" for="alumno_<?= $alumno['id'] ?>">
                    <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']) ?>
                    <?= $estaEnOtroGrupo && !$estaEnEsteGrupo ? '(Ya asignado a otra carrera)' : '' ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-primary">Guardar asignaciones</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require '../../includes/footer.php'; ?>