<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nombre");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(0, 0, 0);
            color: white;
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
<div class="container mt-4">
   <div class="top-bar">
        <h2>Gestión de Usuarios</h2>
        <div class="text-end">
            <a href="usuario_nuevo.php" class="btn btn-success">+ Agregar Usuario</a>
            <br>
            <a href="../admin/dashboard.php" class="btn btn-secondary btn-volver mt-2">← Volver al Dashboard</a>
        </div>
    </div>
    <table class="table table-bordered table-hover">
        <thead class="thead-red">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Puntos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= $u['rol'] ?></td>
                <td><?= $u['puntos'] ?></td>
                <td>
                    <a href="usuario_editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <?php if ($_SESSION['user_id'] != $u['id']): ?>
                        <a href="usuario_eliminar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
