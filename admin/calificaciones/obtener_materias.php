<?php
require '../../includes/db.php';

if (!isset($_GET['grupo_id'])) {
    echo json_encode([]);
    exit;
}

$grupo_id = (int) $_GET['grupo_id'];

$stmt = $pdo->prepare("
    SELECT m.id, m.nombre 
    FROM materias m
    INNER JOIN grupos_materias gm ON m.id = gm.materia_id
    WHERE gm.grupo_id = ?
    ORDER BY m.nombre
");
$stmt->execute([$grupo_id]);

$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($materias);
