<?php
require '../../includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM calificaciones WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
