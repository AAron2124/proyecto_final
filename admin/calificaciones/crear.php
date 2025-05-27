<?php
require '../../includes/db.php';

$alumnos = $pdo->query("SELECT id, nombre FROM alumnos")->fetchAll();
$grupos = $pdo->query("SELECT id, nombre FROM grupos")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alumno = $_POST['id_alumno'];
    $id_grupo = $_POST['id_grupo'];
    $calificacion = $_POST['calificacion'];

    $stmt = $pdo->prepare("INSERT INTO calificaciones (id_alumno, id_grupo, calificacion) VALUES (?, ?, ?)");
    $stmt->execute([$id_alumno, $id_grupo, $calificacion]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Agregar Calificación</title></head>
<body>
<h1>Nueva Calificación</h1>
<form method="post">
    <label>Alumno:</label>
    <select name="id_alumno" required>
        <option value="">Seleccionar alumno</option>
        <?php foreach ($alumnos as $a): ?>
            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Grupo:</label>
    <select name="id_grupo" required>
        <option value="">Seleccionar grupo</option>
        <?php foreach ($grupos as $g): ?>
            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <input type="number" name="calificacion" step="0.01" min="0" max="100" required placeholder="Calificación"><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
