<?php
require '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $especialidad = $_POST['especialidad'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO profesores (nombre, apellido, especialidad, correo, telefono, usuario, contrasena)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $apellido, $especialidad, $correo, $telefono, $usuario, $contrasena]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agregar Profesor</title>
</head>
<body>
<h1>Nuevo Profesor</h1>
<form method="post">
    <input name="nombre" placeholder="Nombre" required><br>
    <input name="apellido" placeholder="Apellido" required><br>
    <input name="especialidad" placeholder="Especialidad" required><br>
    <input name="correo" type="email" placeholder="Correo" required><br>
    <input name="telefono" placeholder="Teléfono"><br>
    <input name="usuario" placeholder="Usuario" required><br>
    <input name="contrasena" type="password" placeholder="Contraseña" required><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
