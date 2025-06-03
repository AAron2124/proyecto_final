<?php
// No hay q llamar a session_start si ya está en funciones.php
require '../includes/db.php';
require '../includes/funciones.php';
require '../includes/header.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // Preparar consulta con parámetro para evitar SQL injection
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :usuario");
    $stmt->execute(['usuario' => $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Compara directamente usuario y contraseña en texto plano
    if ($user && $password === $user['password']) {
        // Inicia sesión si no está iniciada 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['usuario_id'] = $user['id'];

        if ($user['rol'] === 'admin') {
            header("Location: ../admin/index.php");
            exit;
        } else {
            header("Location: ../alumno/index.php");
            exit;
        }
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header text-center bg-primary text-white">
                <h4>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
