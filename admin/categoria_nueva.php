<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
    header("Location: categorias_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Nueva Categoría</h2>
    <form method="POST">
        <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre de la categoría" required>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="categorias_gestionar.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
