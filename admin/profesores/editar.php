<?php
require '../../includes/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM profesores WHERE id = ?");
$stmt->execute([$id]);
$profesor = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE profesores SET nombre = ?, apellido = ?, especialidad = ?, correo = ?, 
            telefono = ?, usuario = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nombre'], $_POST['apellido'], $_POST['especialidad'],
        $_POST['correo'], $_POST['telefono'], $_POST['usuario'], $id
    ]);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Editar Profesor</title></head>
<body>
<h1>Editar Profesor</h1>
<form method="post">
    <input name="nombre" value="<?= $profesor['nombre'] ?>" required><br>
    <input name="apellido" value="<?= $profesor['apellido'] ?>" required><br>
    <input name="especialidad" value="<?= $profesor['especialidad'] ?>" required><br>
    <input name="correo" type="email" value="<?= $profesor['correo'] ?>" required><br>
    <input name="telefono" value="<?= $profesor['telefono'] ?>"><br>
    <input name="usuario" value="<?= $profesor['usuario'] ?>" required><br>
    <button type="submit">Actualizar</button>
</form>
</body>
</html>
