<?php
require '../../includes/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE alumnos SET nombre = ?, apellido = ?, fecha_nacimiento = ?, direccion = ?, 
            telefono = ?, correo = ?, usuario = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'],
        $_POST['direccion'], $_POST['telefono'], $_POST['correo'], $_POST['usuario'], $id
    ]);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Editar Alumno</title></head>
<body>
<h1>Editar Alumno</h1>
<form method="post">
    <input name="nombre" value="<?= $alumno['nombre'] ?>" required><br>
    <input name="apellido" value="<?= $alumno['apellido'] ?>" required><br>
    <input type="date" name="fecha_nacimiento" value="<?= $alumno['fecha_nacimiento'] ?>" required><br>
    <input name="direccion" value="<?= $alumno['direccion'] ?>"><br>
    <input name="telefono" value="<?= $alumno['telefono'] ?>"><br>
    <input type="email" name="correo" value="<?= $alumno['correo'] ?>"><br>
    <input name="usuario" value="<?= $alumno['usuario'] ?>" required><br>
    <button type="submit">Actualizar</button>
</form>
</body>
</html>
