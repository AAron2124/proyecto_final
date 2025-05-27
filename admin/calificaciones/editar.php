<?php
require '../../includes/conexion.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM calificaciones WHERE id = ?");
$stmt->execute([$id]);
$cal = $stmt->fetch();

$alumnos = $pdo->query("SELECT id, nombre FROM alumnos")->fetchAll();
$grupos = $pdo->query("SELECT id, nombre FROM grupos")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alumno = $_POST['id_alumno'];
    $id_grupo = $_POST['id_grupo'];
    $calificacion = $_POST['calificacion'];

    $sql = "UPDATE calificaciones SET id_alumno = ?, id_grupo = ?, calificacion = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_alumno, $id_grupo, $calificacion, $id]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Editar Calificación</title></head>
<body>
<h1>Editar Calificación</h1>
<form method="post">
    <label>Alumno:</label>
    <select name="id_alumno" required>
        <?php foreach ($alumnos as $a): ?>
            <option value="<?= $a['id'] ?>" <?= $cal['id_alumno'] == $a['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Grupo:</label>
    <select name="id_grupo" required>
        <?php foreach ($grupos as $g): ?>
            <option value="<?= $g['id'] ?>" <?= $cal['id_grupo'] == $g['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($g['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <input type="number" name="calificacion" value="<?= $cal['calificacion'] ?>" step="0.01" min="0" max="100" required><br>
    <button type="submit">Actualizar</button>
</form>
</body>
</html>
