<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';
include '../../includes/header.php';

if (!isset($_GET['id'])) {
    echo "<p class='alert alert-danger'>ID de alumno no especificado.</p>";
    include '../../includes/footer.php';
    exit;
}

$id = intval($_GET['id']);

// Procesar formulario al enviarlo jeje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';


    // Actualizar en BD
    $sql = "UPDATE alumnos SET nombre = ?, apellido = ?, fecha_nacimiento = ?, direccion = ?, telefono = ?, correo = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $direccion, $telefono, $correo, $id]);

    echo "<div class='alert alert-success'>Alumno actualizado correctamente.</div>";
}

// Obtener datos actuales del alumno
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    echo "<p class='alert alert-danger'>Alumno no encontrado.</p>";
    include '../../includes/footer.php';
    exit;
}
?>

<h2 class="mb-4">Editar Alumno</h2>

<form method="post" action="editar.php?id=<?= $id ?>">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" 
            value="<?= htmlspecialchars($alumno['nombre']) ?>" 
            required 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            title="Solo letras y espacios permitidos">
    </div>
    <div class="mb-3">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellido" name="apellido" 
            value="<?= htmlspecialchars($alumno['apellido']) ?>" 
            required 
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            title="Solo letras y espacios permitidos">
    </div>
    <div class="mb-3">
        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $alumno['fecha_nacimiento'] ?>">
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($alumno['direccion']) ?>">
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" 
            value="<?= htmlspecialchars($alumno['telefono']) ?>" 
            pattern="[0-9]{7,15}" 
            title="Solo números (entre 7 y 15 dígitos)">
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($alumno['correo']) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>


<?php include '../../includes/footer.php'; ?>
