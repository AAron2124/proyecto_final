<?php
require '../../includes/db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM grupos WHERE id = ?");
$stmt->execute([$id]);
$grupo = $stmt->fetch();

$materias = $pdo->query("SELECT id, nombre FROM materias")->fetchAll();
$profesores = $pdo->query("SELECT id, nombre FROM profesores")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_materia = $_POST['id_materia'];
    $id_profesor = $_POST['id_profesor'];
    $horario = $_POST['horario'];

    $sql = "UPDATE grupos SET nombre = ?, id_materia = ?, id_profesor = ?, horario = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $id_materia, $id_profesor, $horario, $id]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Editar Grupo</title></head>
<body>
<h1>Editar Grupo</h1>
<form method="post">
    <input name="nombre" value="<?= $grupo['nombre'] ?>" required><br>

    <label>Materia:</label>
    <select name="id_materia" required>
        <?php foreach ($materias as $m): ?>
            <option value="<?= $m['id'] ?>" <?= $grupo['id_materia'] == $m['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($m['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Profesor:</label>
    <select name="id_profesor" required>
        <?php foreach ($profesores as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $grupo['id_profesor'] == $p['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <input name="horario" value="<?= $grupo['horario'] ?>" required><br>
    <button type="submit">Actualizar</button>
</form>
</body>
</html>
