<?php
require '../../includes/db.php';

$materias = $pdo->query("SELECT id, nombre FROM materias")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre FROM profesores")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_materia = $_POST['id_materia'];
    $id_profesor = $_POST['id_profesor'];
    $horario = $_POST['horario'];

    $sql = "INSERT INTO grupos (nombre, id_materia, id_profesor, horario) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $id_materia, $id_profesor, $horario]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Agregar Grupo</title></head>
<body>
<h1>Nuevo Grupo</h1>
<form method="post">
    <input name="nombre" placeholder="Nombre del grupo" required><br>
    
    <label>Materia:</label>
    <select name="id_materia" required>
        <option value="">Seleccionar materia</option>
        <?php foreach ($materias as $m): ?>
            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Profesor:</label>
    <select name="id_profesor" required>
        <option value="">Seleccionar profesor</option>
        <?php foreach ($profesores as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <input name="horario" placeholder="Horario (Ej. Lun y Mie 10-12am)" required><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
