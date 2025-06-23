<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$idVenta = $_GET['id'] ?? null;
if (!$idVenta) header("Location: ventas_listado.php");

// Obtener venta y cliente
$stmtVenta = $pdo->prepare("
    SELECT v.*, u.nombre AS cliente 
    FROM ventas v 
    LEFT JOIN usuarios u ON v.id_usuario = u.id 
    WHERE v.id = ?
");
$stmtVenta->execute([$idVenta]);
$venta = $stmtVenta->fetch();

// Obtener productos de la venta
$stmtProductos = $pdo->prepare("
    SELECT dv.*, p.nombre 
    FROM detalle_venta dv 
    JOIN productos p ON dv.id_producto = p.id 
    WHERE dv.id_venta = ?
");
$stmtProductos->execute([$idVenta]);
$items = $stmtProductos->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Venta #<?= $venta['id'] ?></title>
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
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>🧾 Detalle de Venta #<?= $venta['id'] ?></h2>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente'] ?? 'Anónimo') ?></p>
    <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
    <p><strong>Total:</strong> Bs <?= number_format($venta['total'], 2, ',', '.') ?></p>

    <table class="table table-bordered table-sm table-hover mt-3">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i): ?>
                <tr>
                    <td><?= $i['nombre'] ?></td>
                    <td><?= $i['cantidad'] ?></td>
                    <td>Bs <?= number_format($i['subtotal'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="ventas_listado.php" class="btn btn-secondary mt-3">← Volver</a>
</div>
</body>
</html>
