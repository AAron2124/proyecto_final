<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

// Consultas para obtener los totales
$totalAlumnos = $pdo->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
$totalProfesores = $pdo->query("SELECT COUNT(*) FROM profesores")->fetchColumn();
$totalMaterias = $pdo->query("SELECT COUNT(*) FROM materias")->fetchColumn();
$totalGrupos = $pdo->query("SELECT COUNT(*) FROM grupos")->fetchColumn();
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
?>

<h2 class="mb-4">Panel del Administrador 📊</h2>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow">
            <div class="card-body">
                <h5 class="card-title">Alumnos</h5>
                <p class="card-text fs-3"><?= $totalAlumnos ?></p>
                <a href="alumnos/index.php" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title">Profesores</h5>
                <p class="card-text fs-3"><?= $totalProfesores ?></p>
                <a href="profesores/index.php" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title">Materias</h5>
                <p class="card-text fs-3"><?= $totalMaterias ?></p>
                <a href="materias/index.php" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info shadow">
            <div class="card-body">
                <h5 class="card-title">Grupos</h5>
                <p class="card-text fs-3"><?= $totalGrupos ?></p>
                <a href="grupos/index.php" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-3">
        <div class="card text-white bg-secondary shadow">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <p class="card-text fs-3"><?= $totalUsuarios ?></p>
                <a href="usuarios.php" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-dark shadow">
            <div class="card-body">
                <h5 class="card-title">Calificaciones</h5>
                <p class="card-text fs-3"><i class="bi bi-journal-text"></i></p>
                <a href="calificaciones/index.php" class="btn btn-light btn-sm">Gestionar</a>
            </div>
        </div>
    </div>
</div>

<a href="/proyecto_final/views/logout.php" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
</a>

<?php require '../includes/footer.php'; ?>
