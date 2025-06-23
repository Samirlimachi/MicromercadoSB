<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$stmt = $pdo->query("SELECT p.*, c.nombre AS categoria 
                     FROM productos p 
                     LEFT JOIN categorias c ON p.id_categoria = c.id 
                     ORDER BY p.nombre");

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(0, 0, 0);
        }

        .table-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            color: red;
        }

        .btn-success {
            font-weight: bold;
        }

        .btn-volver {
            margin-top: 10px;
        }

        .thead-red th {
            color: red;
            text-align: center;
            background-color: black;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="top-bar">
            <h2>Gestión de Productos</h2>
            <div class="text-end">
                <a href="producto_nuevo.php" class="btn btn-success">+ Agregar Producto</a>
                <br>
                <a href="../admin/dashboard.php" class="btn btn-secondary btn-volver mt-2">← Volver al Dashboard</a>
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-red">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Stock Mínimo</th>
                    <th>Vencimiento</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><img src="../<?= $p['imagen'] ?>" class="table-img" alt="Producto"></td>
                        <td><?= $p['nombre'] ?></td>
                        <td><?= $p['descripcion'] ?></td>
                        <td>Bs <?= number_format($p['precio'], 2, ',', '.') ?></td>
                        <td><?= $p['cantidad'] ?></td>
                        <td><?= $p['stock_minimo'] ?></td>
                        <td><?= $p['fecha_vencimiento'] ?? '—' ?></td>
                        <td><?= $p['categoria'] ?? 'Sin categoría' ?></td>
                        <td>
                            <a href="producto_editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="producto_eliminar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>