<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Eliminar calificaciones relacionadas con el grupo
    $stmt = $pdo->prepare("DELETE FROM calificaciones WHERE grupo_id = ?");
    $stmt->execute([$id]);

    // Eliminar asignaciones relacionadas con el grupo
    $stmt = $pdo->prepare("DELETE FROM asignaciones WHERE grupo_id = ?");
    $stmt->execute([$id]);

    // Eliminar asociaciones alumnos-grupos
    $stmt = $pdo->prepare("DELETE FROM alumnos_grupos WHERE grupo_id = ?");
    $stmt->execute([$id]);

    // Eliminar asociaciones grupos-materias
    $stmt = $pdo->prepare("DELETE FROM grupos_materias WHERE grupo_id = ?");
    $stmt->execute([$id]);

    // Finalmente eliminar el grupo
    $stmt = $pdo->prepare("DELETE FROM grupos WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
?>
