<?php
require '../../includes/conexion.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM materias WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
