<?php
session_start();
require_once '../includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $token = bin2hex(random_bytes(16));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $update = $pdo->prepare("UPDATE usuarios SET token = ?, token_expira = ? WHERE id = ?");
        $update->execute([$token, $expira, $usuario['id']]);

        // Redirección con token
        header("Location: nueva_contrasena.php?token=" . $token);
        exit();
    } else {
        $mensaje = "⚠️ Correo no registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña | Micromercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240720/pngtree-supermarket-grocery-store-aisle-and-shelves-blurred-background-image_15896050.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container-recovery {
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
    <div class="container-recovery text-white">
        <h3 class="text-center mb-4">Recuperar Contraseña</h3>

        <?php if ($mensaje): ?>
            <div class="alert alert-danger text-center"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group mb-3">
                <i class="form-icon bi bi-envelope-fill"></i>
                <input type="email" name="correo" class="form-control" placeholder="Correo registrado" required>
            </div>
            <button type="submit" class="btn btn-green w-100">Enviar</button>
        </form>

        <div class="d-flex justify-content-between mt-3">
            <a href="login.php" class="text-white">← Volver al Login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
