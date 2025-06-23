<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$idCliente = $_GET['id'] ?? null;
if (!$idCliente) header("Location: clientes_gestionar.php");

// Obtener datos del cliente
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$idCliente]);
$cliente = $stmt->fetch();

// Obtener ventas del cliente
$stmtVentas = $pdo->prepare("SELECT * FROM ventas WHERE id_usuario = ? ORDER BY fecha DESC");
$stmtVentas->execute([$idCliente]);
$ventas = $stmtVentas->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras</title>
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
    <h2>🧾 Historial de Compras - <?= htmlspecialchars($cliente['nombre']) ?></h2>
    <a href="clientes_gestionar.php" class="btn btn-secondary mb-3">← Volver</a>

    <?php if (count($ventas) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= $venta['id'] ?></td>
                        <td><?= $venta['fecha'] ?></td>
                        <td>Bs <?= number_format($venta['total'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Este cliente aún no ha realizado compras.</div>
    <?php endif; ?>
</div>
</body>
</html>
