<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$stmt = $pdo->query("
    SELECT p.*, c.nombre AS categoria 
    FROM productos p 
    LEFT JOIN categorias c ON p.id_categoria = c.id 
    WHERE p.cantidad <= p.stock_minimo
    ORDER BY p.cantidad ASC
");
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos con Stock Bajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        h2 {
            color: red;
        }
        .img-mini {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        .table-warning {
            background-color: #330000;
        }
        .table-warning th {
            color: red;
            background-color: black;
            text-align: center;
        }
        .alert-success {
            background-color: #333;
            color: #f8f9fa;
            border-color: #555;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>⚠️ Productos con Stock Bajo</h2>
    <div class="text-end mb-3">
        <a href="../admin/dashboard.php" class="btn btn-secondary">← Volver al Dashboard</a>
    </div>

    <?php if (count($productos) > 0): ?>
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-warning">
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Stock Mínimo</th>
                    <th>Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><img src="../<?= $p['imagen'] ?>" class="img-mini" alt="Producto"></td>
                        <td><?= $p['nombre'] ?></td>
                        <td><?= $p['categoria'] ?? 'Sin categoría' ?></td>
                        <td><strong><?= $p['cantidad'] ?></strong></td>
                        <td><?= $p['stock_minimo'] ?></td>
                        <td><?= $p['fecha_vencimiento'] ?? '—' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-success mt-4">✅ Todos los productos tienen stock suficiente.</div>
    <?php endif; ?>
</div>
</body>
</html>
