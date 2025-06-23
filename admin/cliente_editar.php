<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) header("Location: clientes_gestionar.php");

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$cliente = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
    $stmt->execute([$nombre, $id]);
    header("Location: clientes_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        h2 {
            color: red;
        }
        label {
            color: red;
        }
        .form-container {
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2>Editar Cliente</h2>
        <form method="POST">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control mb-3" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="clientes_gestionar.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</body>
</html>
