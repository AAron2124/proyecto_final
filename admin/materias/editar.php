<?php
require '../../includes/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM materias WHERE id = ?");
$stmt->execute([$id]);
$materia = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $clave = $_POST['clave'];
    $creditos = $_POST['creditos'];
    $semestre = $_POST['semestre'];

    $sql = "UPDATE materias SET nombre = ?, clave = ?, creditos = ?, semestre = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $clave, $creditos, $semestre, $id]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Editar Materia</title></head>
<body>
<h1>Editar Materia</h1>
<form method="post">
    <input name="nombre" value="<?= $materia['nombre'] ?>" required><br>
    <input name="clave" value="<?= $materia['clave'] ?>" required><br>
    <input name="creditos" type="number" value="<?= $materia['creditos'] ?>" required><br>
    <input name="semestre" type="number" value="<?= $materia['semestre'] ?>" required><br>
    <button type="submit">Actualizar</button>
</form>
</body>
</html>
