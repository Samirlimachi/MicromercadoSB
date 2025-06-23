<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$stmt = $pdo->prepare("
    SELECT v.*, u.nombre AS cliente 
    FROM ventas v 
    LEFT JOIN usuarios u ON v.id_usuario = u.id 
    ORDER BY v.fecha DESC
");
$stmt->execute();
$ventas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas Registradas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }
        h2 {
            color: red;
        }
        .table th {
            color: red;
            background-color: black;
            text-align: center;
        }
        .table td {
            background-color: #1c1c1c;
            color: white;
        }
        .btn-info {
            background-color: red;
            border: none;
        }
        .btn-info:hover {
            background-color: darkred;
        }
        .btn-volver {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>📋 Ventas Registradas</h2>
    <a href="../admin/dashboard.php" class="btn btn-secondary btn-volver">← Volver al Dashboard</a>

    <table class="table table-bordered table-hover mt-3">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $v): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= htmlspecialchars($v['cliente'] ?? 'Anónimo') ?></td>
                <td><?= $v['fecha'] ?></td>
                <td>Bs <?= number_format($v['total'], 2, ',', '.') ?></td>
                <td>
                    <a href="venta_detalle.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-info">Ver Detalle</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
