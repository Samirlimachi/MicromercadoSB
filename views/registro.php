<?php
require_once '../includes/db.php';

$mensajeError = '';
$mensajeExito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $confirmar = $_POST['confirmar'];

    // Validaciones
    if ($contrasena !== $confirmar) {
        $mensajeError = "⚠️ Las contraseñas no coinciden.";
    } elseif (
        strlen($contrasena) < 8 ||
        !preg_match('/[A-Z]/', $contrasena) ||
        !preg_match('/[a-z]/', $contrasena) ||
        !preg_match('/[0-9]/', $contrasena) ||
        !preg_match('/[\W_]/', $contrasena)
    ) {
        $mensajeError = "⚠️ La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);

        if ($stmt->fetch()) {
            $mensajeError = "⚠️ Ya existe un usuario con ese correo.";
        
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, 'cliente')");

            $stmt->execute([$nombre, $correo, $hash]);

            $mensajeExito = "✅ Registro exitoso. Ahora puedes iniciar sesión.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240720/pngtree-supermarket-grocery-store-aisle-and-shelves-blurred-background-image_15896050.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .registro-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(6px);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            margin: auto;
            margin-top: 8%;
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
    </style>
</head>
<body>
    <div class="registro-container text-white">
        <h3 class="text-center mb-4">Crear Cuenta</h3>

        <?php if ($mensajeError): ?>
            <div class="alert alert-danger"><?= $mensajeError ?></div>
        <?php endif; ?>
        <?php if ($mensajeExito): ?>
            <div class="alert alert-success"><?= $mensajeExito ?></div>
        <?php endif; ?>

        
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
            </div>
            <div class="mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="mb-3">
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña segura" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirmar" class="form-control" placeholder="Confirmar contraseña" required>
            </div>
            <button type="submit" class="btn btn-green w-100">Registrar</button>
            
        </form>

        <div class="d-flex justify-content-center mt-3">
            <a href="login.php" class="text-white">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</body>
</html>
