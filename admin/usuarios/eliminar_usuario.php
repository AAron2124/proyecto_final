<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';

// Validar que el ID fue proporcionado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$usuario_id = $_GET['id'];

// Verificar que el usuario exista
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die("Usuario no encontrado.");
}

// Verificar si hay un alumno relacionado a este usuario
$stmt = $pdo->prepare("SELECT id FROM alumnos WHERE usuario_id = ?");
$stmt->execute([$usuario_id]);
$alumno = $stmt->fetch();

if ($alumno) {
    $alumno_id = $alumno['id'];

    // Eliminar las relaciones del alumno en alumnos_grupos
    $stmt = $pdo->prepare("DELETE FROM alumnos_grupos WHERE alumno_id = ?");
    $stmt->execute([$alumno_id]);

    // Eliminar al alumno
    $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = ?");
    $stmt->execute([$alumno_id]);
}

// Ahora sí, eliminar al usuario
$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);

// Redirigir al listado de usuarios
header("Location: usuarios.php");
exit;
