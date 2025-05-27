<?php
require '../../includes/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
