<?php
require '../../includes/db.php';
header('Content-Type: application/json');

if (!isset($_GET['grupo_id']) || !isset($_GET['materia_id'])) {
    echo json_encode([]);
    exit;
}

$grupo_id = (int)$_GET['grupo_id'];
$materia_id = (int)$_GET['materia_id'];

// Obtener profesores asignados a esa materia y grupo
$stmt = $pdo->prepare("
    SELECT p.id, p.nombre, p.apellido
    FROM asignaciones a
    JOIN profesores p ON a.profesor_id = p.id
    WHERE a.grupo_id = ? AND a.materia_id = ?
    ORDER BY p.nombre
");
$stmt->execute([$grupo_id, $materia_id]);
$profesores = $stmt->fetchAll();

echo json_encode($profesores);
