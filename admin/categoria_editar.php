<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) header("Location: categorias_gestionar.php");

$categoria = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
$categoria->execute([$id]);
$categoria = $categoria->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
    $stmt->execute([$nombre, $id]);
    header("Location: categorias_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        h2 {
            color: red;
        }
        .form-control {
            background-color: #1e1e1e;
            color: white;
            border: 1px solid #555;
        }
        .form-control::placeholder {
            color: #aaa;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Editar Categoría</h2>
    <form method="POST">
        <input type="text" name="nombre" class="form-control mb-3" value="<?= $categoria['nombre'] ?>" required>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="categorias_gestionar.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
