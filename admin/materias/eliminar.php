<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Eliminar relaciones en grupos_materias
        $stmt = $pdo->prepare("DELETE FROM grupos_materias WHERE materia_id = ?");
        $stmt->execute([$id]);

        // Eliminar relaciones en asignaciones
        $stmt = $pdo->prepare("DELETE FROM asignaciones WHERE materia_id = ?");
        $stmt->execute([$id]);

        // Eliminar relaciones en calificaciones
        $stmt = $pdo->prepare("DELETE FROM calificaciones WHERE materia_id = ?");
        $stmt->execute([$id]);

        // Finalmente, eliminar la materia
        $stmt = $pdo->prepare("DELETE FROM materias WHERE id = ?");
        $stmt->execute([$id]);

        // Confirmar la transacción
        $pdo->commit();
    } catch (Exception $e) {
        // Revertir en caso de error
        $pdo->rollBack();
        echo "Error al eliminar la materia: " . $e->getMessage();
        exit;
    }
}

header("Location: index.php");
exit;
