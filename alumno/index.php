<?php
session_start();
require '../includes/db.php';
require '../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'alumno') {
    header("Location: ../views/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener información del alumno
$stmt = $pdo->prepare("SELECT a.id, a.nombre, a.apellido, g.nombre AS grupo, g.nivel
    FROM alumnos a
    LEFT JOIN alumnos_grupos ag ON a.id = ag.alumno_id
    LEFT JOIN grupos g ON ag.grupo_id = g.id
    WHERE a.usuario_id = ?");
$stmt->execute([$usuario_id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    echo "<div class='alert alert-danger'>No se encontró el alumno vinculado a este usuario.</div>";
    require '../includes/footer.php';
    exit;
}

$alumno_id = $alumno['id'];

// Obtener número de materias inscritas
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT a.materia_id) AS total_materias
    FROM asignaciones a
    JOIN calificaciones c ON a.id = c.asignacion_id
    WHERE c.alumno_id = ?
");
$stmt->execute([$alumno_id]);
$totalMaterias = $stmt->fetchColumn();
?>

<h2 class="mb-4">Bienvenido, <?= htmlspecialchars($alumno['nombre']) ?> 👋</h2>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-primary shadow">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="bi bi-book"></i> Materias Inscritas</h5>
                <p class="card-text fs-4"><?= $totalMaterias ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success shadow">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="bi bi-people"></i> Grupo</h5>
                <p class="card-text fs-5"><?= $alumno['grupo'] ?> (<?= $alumno['nivel'] ?>)</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info shadow">
            <div class="card-body">
                <h5 class="card-title text-info"><i class="bi bi-person"></i> Alumno</h5>
                <p class="card-text"><?= $alumno['nombre'] . ' ' . $alumno['apellido'] ?></p>
            </div>
        </div>
    </div>
</div>

<div class="mt-5 d-flex gap-3">
    <a href="mis_calificaciones.php" class="btn btn-primary">
        <i class="bi bi-card-checklist"></i> Ver Calificaciones
    </a>
    <a href="../logout.php" class="btn btn-danger">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
</div>

<?php require '../includes/footer.php'; ?>
