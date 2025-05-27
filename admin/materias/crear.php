<?php
require '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $clave = $_POST['clave'];
    $creditos = $_POST['creditos'];
    $semestre = $_POST['semestre'];

    $sql = "INSERT INTO materias (nombre, clave, creditos, semestre) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $clave, $creditos, $semestre]);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Agregar Materia</title></head>
<body>
<h1>Nueva Materia</h1>
<form method="post">
    <input name="nombre" placeholder="Nombre" required><br>
    <input name="clave" placeholder="Clave única" required><br>
    <input name="creditos" type="number" min="1" placeholder="Créditos" required><br>
    <input name="semestre" type="number" min="1" max="12" placeholder="Semestre" required><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
