<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../cliente/productos_ver.php');
        }
        exit();
    } else {
        $error = "⚠️ Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Micromercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240720/pngtree-supermarket-grocery-store-aisle-and-shelves-blurred-background-image_15896050.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(6px);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            margin: auto;
            margin-top: 10%;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .form-control {
            background-color: rgba(255,255,255,0.7);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #28a745;
        }

        .btn-green {
            background-color: #28a745;
            border: none;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        .form-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .form-group {
            position: relative;
        }

        .form-group input {
            padding-left: 35px;
        }
    </style>
</head>
<body>
    <div class="login-container text-white">
        <h3 class="text-center mb-4">Login</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group mb-3">
                <i class="form-icon bi bi-person-fill"></i>
                <input type="email" name="correo" class="form-control" placeholder="Correo" required>
            </div>
            <div class="form-group mb-3">
                <i class="form-icon bi bi-key-fill"></i>
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn btn-green w-100">Login</button>
        </form>

        <div class="d-flex justify-content-between mt-3">
            <a href="registro.php" class="text-white">¿No tienes una cuenta?</a>
            <a href="recuperar_contrasena.php" class="text-white">Olvidé mi contraseña</a>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
