<?php
require '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO alumnos (nombre, apellido, fecha_nacimiento, direccion, telefono, correo, usuario, contrasena)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $apellido, $fecha, $direccion, $telefono, $correo, $usuario, $contrasena]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agregar Alumno</title>
</head>
<body>
<h1>Nuevo Alumno</h1>
<form method="post">
    <input name="nombre" placeholder="Nombre" required><br>
    <input name="apellido" placeholder="Apellido" required><br>
    <input name="fecha_nacimiento" type="date" required><br>
    <input name="direccion" placeholder="Dirección"><br>
    <input name="telefono" placeholder="Teléfono"><br>
    <input name="correo" type="email" placeholder="Correo"><br>
    <input name="usuario" placeholder="Usuario" required><br>
    <input name="contrasena" type="password" placeholder="Contraseña" required><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
