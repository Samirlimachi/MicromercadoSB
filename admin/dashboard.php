<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT COUNT(*) AS total_ventas, SUM(total) AS total_recaudado FROM ventas");
$stats = $stmt->fetch();

$totalVentas = $stats['total_ventas'] ?? 0;
$totalRecaudado = $stats['total_recaudado'] ?? 0.00;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Micromercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: white;
            font-family: Arial, sans-serif;
        }

        .titulo {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .btn-cerrar {
            background-color: white;
            color: black;
            font-weight: bold;
        }

        .btn-icono {
            background-color: red;
            border: none;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            color: white;
            font-weight: bold;
            width: 100%;
            height: 100%;
        }

        .btn-icono img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .info-panel {
            margin-top: 30px;
            text-align: center;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap px-3">
            <h1 class="titulo">Menú Administrador</h1>
            <a href="../views/login.php" class="btn btn-cerrar mt-2 mt-sm-0">Cerrar Sesión</a>
        </div>

        <div class="row g-3 mt-4 justify-content-center px-3">
            <?php
            $paneles = [
                ["productos_gestionar.php", "https://cdn-icons-png.flaticon.com/512/3144/3144460.png", "Gestión de Productos"],
                ["usuarios_gestionar.php", "https://cdn-icons-png.flaticon.com/512/747/747376.png", "Gestión de Usuarios"],
                ["productos_mas_vendidos.php", "https://cdn-icons-png.flaticon.com/512/1040/1040230.png", "Productos más vendidos"],
                ["productos_stock_bajo.php", "https://cdn-icons-png.flaticon.com/512/1034/1034151.png", "Productos con Stock Bajo"],
                ["categorias_gestionar.php", "https://cdn-icons-png.flaticon.com/512/869/869636.png", "Gestión de Categorías"],
                ["clientes_gestionar.php", "https://cdn-icons-png.flaticon.com/512/2922/2922510.png", "Clientes Frecuentes"],
                ["ventas_listado.php", "https://cdn-icons-png.flaticon.com/512/1250/1250613.png", "Ventas Registradas"],
                ["ventas_reporte_pdf.php", "https://cdn-icons-png.flaticon.com/512/337/337946.png", "Informe de Ventas", "#ffc107", "black"]
            ];
            foreach ($paneles as $panel) {
                $url = $panel[0];
                $img = $panel[1];
                $text = $panel[2];
                $bg = $panel[3] ?? "red";
                $color = $panel[4] ?? "white";
                echo <<<HTML
            <div class="col-6 col-sm-4 col-md-3">
                <a href="$url" class="btn-icono d-block" style="background-color: $bg; color: $color;">
                    <img src="$img" alt="Icono">
                    <div>$text</div>
                </a>
            </div>
            HTML;
            }
            ?>
        </div>

        <div class="info-panel">
            <p><strong>Total Recaudado:</strong> Bs <?= number_format($totalRecaudado, 2, ',', '.') ?></p>
            <p><strong>Total Ventas:</strong> <?= $totalVentas ?></p>
        </div>
    </div>
</body>

</html>