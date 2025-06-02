<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Escolar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="/proyecto_final/includes/bootstrap.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/proyecto_final/alumno/index.php">Gestión Escolar</a>
            <div>
                <?php if (isset($_SESSION['usuario'])): ?>
                    <span class="text-white me-3">Hola, <?= htmlspecialchars($_SESSION['usuario']) ?></span>
                    <a href="/proyecto_final/views/logout.php" class="btn btn-light btn-sm">Cerrar sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container">
