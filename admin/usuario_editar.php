<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) header("Location: usuarios_gestionar.php");

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?");
    $stmt->execute([$nombre, $rol, $id]);
    header("Location: usuarios_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
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
        .btn-primary {
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
    <h2>Editar Usuario</h2>
    <form method="POST">
        <input type="text" name="nombre" class="form-control mb-3" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        <select name="rol" class="form-control mb-4" required>
            <option value="cliente" <?= $usuario['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
            <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="usuarios_gestionar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

</body>
</html>
