<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$stmt = $pdo->query("
    SELECT 
        p.nombre, 
        p.imagen,
        SUM(dv.cantidad) AS total_vendido,
        SUM(dv.subtotal) AS total_recaudado
    FROM detalle_venta dv
    JOIN productos p ON dv.id_producto = p.id
    GROUP BY dv.id_producto
    ORDER BY total_vendido DESC
    LIMIT 10
");
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos Más Vendidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        h2 {
            color: red;
        }
        .card-producto {
            background-color: #1a1a1a;
            border: 1px solid #444;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .img-producto {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #888;
        }
        .alert-info {
            background-color: #333;
            color: #f8f9fa;
            border-color: #555;
        }
        .btn-volver {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>🔥 Productos Más Vendidos</h2>
    <div class="text-end mb-3">
        <a href="../admin/dashboard.php" class="btn btn-secondary btn-volver">← Volver al Dashboard</a>
    </div>

    <?php if (count($productos) > 0): ?>
        <div class="row mt-4">
            <?php foreach ($productos as $p): ?>
                <div class="col-md-6">
                    <div class="card-producto d-flex align-items-center gap-3">
                        <img src="../<?= $p['imagen'] ?>" class="img-producto" alt="Producto">
                        <div>
                            <h5 class="text-white"><?= $p['nombre'] ?></h5>
                            <p>Unidades vendidas: <strong><?= $p['total_vendido'] ?></strong></p>
                            <p>Total recaudado: <strong>Bs <?= number_format($p['total_recaudado'], 2, ',', '.') ?></strong></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">Aún no se han registrado ventas.</div>
    <?php endif; ?>
</div>
</body>
</html>
