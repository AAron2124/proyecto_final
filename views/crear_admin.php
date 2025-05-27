<?php
require 'includes/conexion.php';

$usuario = "admin";
$pass = password_hash("admin123", PASSWORD_DEFAULT);
$rol = "admin";

$stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES (?, ?, ?)");
$stmt->execute([$usuario, $pass, $rol]);

echo "Usuario administrador creado.";
