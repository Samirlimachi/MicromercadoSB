<?php
session_start();
require_once '../includes/db.php';
//que hace esta línea?
if (!isset($_GET['token'])) {
    // Verifica si el token está presente en la URL
    die("Token no válido.");
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token = ?");
$stmt->execute([$token]);
$usuario = $stmt->fetch();

if (!$usuario || strtotime($usuario['token_expira']) < time()) {
    die("Token expirado o inválido.");
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['contrasena'];
    $pass2 = $_POST['confirmar'];

    if ($pass1 !== $pass2) {
        $mensaje = "⚠️ Las contraseñas no coinciden.";
    } elseif (strlen($pass1) < 8 || !preg_match('/[A-Z]/', $pass1) || !preg_match('/[0-9]/', $pass1)) {
        $mensaje = "⚠️ La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.";
    } else {
        $nueva = password_hash($pass1, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = ?, token = NULL, token_expira = NULL WHERE id = ?");
        $stmt->execute([$nueva, $usuario['id']]);

        echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Contraseña | Micromercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240720/pngtree-supermarket-grocery-store-aisle-and-shelves-blurred-background-image_15896050.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .reset-container {
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
    <div class="reset-container text-white">
        <h3 class="text-center mb-4">Nueva Contraseña</h3>

        <?php if ($mensaje): ?>
            <div class="alert alert-danger text-center"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group mb-3">
                <i class="form-icon bi bi-lock-fill"></i>
                <input type="password" name="contrasena" class="form-control" placeholder="Nueva contraseña" required>
            </div>
            <div class="form-group mb-3">
                <i class="form-icon bi bi-lock-fill"></i>
                <input type="password" name="confirmar" class="form-control" placeholder="Confirmar contraseña" required>
            </div>
            <button type="submit" class="btn btn-green w-100">Actualizar Contraseña</button>
          
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
