<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../includes/db.php';

// Validar que el ID fue proporcionado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de alumno no proporcionado.");
}

$alumno_id = $_GET['id'];

// Buscar el usuario_id asociado al alumno
$stmt = $pdo->prepare("SELECT usuario_id FROM alumnos WHERE id = ?");
$stmt->execute([$alumno_id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    die("Alumno no encontrado.");
}

$usuario_id = $alumno['usuario_id'];

// Eliminar al usuario (esto eliminará también al alumno gracias a ON DELETE CASCADE)
$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);

// Redirigir de vuelta al listado
header("Location: index.php");
exit;
