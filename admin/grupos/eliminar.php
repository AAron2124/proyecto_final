<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Primero eliminar las asociaciones con alumnos
    $stmt = $pdo->prepare("DELETE FROM alumnos_grupos WHERE grupo_id = ?");
    $stmt->execute([$id]);

    // Luego eliminar el grupo
    $stmt = $pdo->prepare("DELETE FROM grupos WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
