<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $contrasena, $rol]);

    header("Location: usuarios_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(0, 0, 0);
            color: white;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #1a1a1a;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }
        h2 {
            color: red;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-control {
            background-color: #2b2b2b;
            color: white;
            border: 1px solid #555;
        }
        .form-control::placeholder {
            color: #aaa;
        }
        .btn-success {
            background-color: red;
            border: none;
        }
        .btn-secondary {
            background-color: #555;
            color: white;
            border: none;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Agregar Nuevo Usuario</h2>
    <form method="POST">
        <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre" required>
        <input type="email" name="correo" class="form-control mb-3" placeholder="Correo" required>
        <input type="password" name="contrasena" class="form-control mb-3" placeholder="Contraseña" required>
        <select name="rol" class="form-control mb-4" required>
            <option value="cliente">Cliente</option>
            <option value="admin">Administrador</option>
        </select>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="usuarios_gestionar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
