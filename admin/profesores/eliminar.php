<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

// Primero eliminar calificaciones donde profesor_id = $id
$stmt = $pdo->prepare("DELETE FROM calificaciones WHERE profesor_id = ?");
$stmt->execute([$id]);

// Luego eliminar asignaciones donde profesor_id = $id
$stmt = $pdo->prepare("DELETE FROM asignaciones WHERE profesor_id = ?");
$stmt->execute([$id]);

// Finalmente eliminar el profesor
$stmt = $pdo->prepare("DELETE FROM profesores WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>
