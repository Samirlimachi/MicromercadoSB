<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

// Obtener solo clientes
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE rol = 'cliente' ORDER BY nombre");
$stmt->execute();
$clientes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes Frecuentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        h2 {
            color: red;
        }
        .thead-red th {
            color: red;
            background-color: black;
            text-align: center;
        }
        .btn-volver {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="top-bar">
        <h2>Clientes Frecuentes</h2>
        <div class="text-end">
            <a href="../admin/dashboard.php" class="btn btn-secondary btn-volver">← Volver al Dashboard</a>
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="thead-red">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Puntos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= $c['nombre'] ?></td>
                <td><?= $c['correo'] ?></td>
                <td><?= $c['puntos'] ?></td>
                <td>
                    <a href="cliente_editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="cliente_puntos_reset.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Reiniciar puntos del cliente?')">Reset Puntos</a>
                    <a href="cliente_historial.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-info">Ver Historial</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
