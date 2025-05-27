<?php
require '../../includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM grupos WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
