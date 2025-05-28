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

// Opcional: podrías agregar confirmación aquí con GET/POST, pero por simplicidad eliminamos directo

$stmt = $pdo->prepare("DELETE FROM profesores WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>
