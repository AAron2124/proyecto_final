<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Eliminar la calificación por id
$stmt = $pdo->prepare("DELETE FROM calificaciones WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>
