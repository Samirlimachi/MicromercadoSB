<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        h2 {
            color: red;
        }
        .table thead th {
            background-color: black;
            color: red;
            text-align: center;
        }
        .btn-volver {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Categorías de Productos</h2>

    <a href="categoria_nueva.php" class="btn btn-success mb-2">+ Nueva Categoría</a>
    <br>
    <a href="../admin/dashboard.php" class="btn btn-secondary btn-volver">← Volver al Dashboard</a>

    <table class="table table-bordered table-hover mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= $c['nombre'] ?></td>
                <td>
                    <a href="categoria_editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="categoria_eliminar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
