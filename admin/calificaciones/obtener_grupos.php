<?php
require '../../includes/db.php';

if (!isset($_GET['alumno_id'])) {
    echo json_encode([]);
    exit;
}

$alumno_id = $_GET['alumno_id'];
$stmt = $pdo->prepare("
    SELECT g.id, g.nombre 
    FROM grupos g
    INNER JOIN alumnos_grupos ag ON g.id = ag.grupo_id
    WHERE ag.alumno_id = ?
");
$stmt->execute([$alumno_id]);
$grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($grupos);
