<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) header("Location: productos_gestionar.php");

$producto = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$producto->execute([$id]);
$producto = $producto->fetch();

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $cantidad = intval($_POST['cantidad']);
    $stock_minimo = intval($_POST['stock_minimo']);
    $vencimiento = $_POST['fecha_vencimiento'] ?? null;
    $id_categoria = $_POST['id_categoria'] ?? null;

    // Validaciones
    if ($precio <= 0 || $cantidad < 0 || $stock_minimo < 0) {
        die("Error: No se permiten valores negativos ni precio cero.");
    }

    $ruta = $producto['imagen'];
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen']['name'];
        $tmp = $_FILES['imagen']['tmp_name'];
        $ruta = 'uploads/' . basename($imagen);
        move_uploaded_file($tmp, "../$ruta");
    }

    $stmt = $pdo->prepare("UPDATE productos SET 
        nombre = ?, descripcion = ?, precio = ?, imagen = ?, cantidad = ?, id_categoria = ?, fecha_vencimiento = ?, stock_minimo = ?
        WHERE id = ?");
    $stmt->execute([$nombre, $descripcion, $precio, $ruta, $cantidad, $id_categoria, $vencimiento, $stock_minimo, $id]);

    header("Location: productos_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://png.pngtree.com/thumb_back/fh260/background/20240720/pngtree-supermarket-grocery-store-aisle-and-shelves-blurred-background-image_15896050.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .form-container {
            background: rgba(255, 0, 0, 0.8);
            padding: 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            margin: auto;
            margin-top: 5%;
            color: white;
        }
        label {
            font-weight: bold;
        }
        .btn-success {
            background-color: green;
            border: none;
            font-weight: bold;
        }
        .btn-secondary {
            font-weight: bold;
        }
        .form-control, .form-select {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h3 class="text-center mb-4">EDITAR PRODUCTO</h3>
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" class="form-control" value="<?= $producto['nombre'] ?>" required>

        <label>Descripción:</label>
        <textarea name="descripcion" class="form-control"><?= $producto['descripcion'] ?></textarea>

        <label>Precio Bs:</label>
        <input type="number" step="0.01" name="precio" class="form-control" min="0.01" value="<?= $producto['precio'] ?>" required>

        <label>Stock:</label>
        <input type="number" name="cantidad" class="form-control" min="0" value="<?= $producto['cantidad'] ?>" required>

        <label>Stock Mínimo:</label>
        <input type="number" name="stock_minimo" class="form-control" min="0" value="<?= $producto['stock_minimo'] ?>" required>

        <label>Fecha Vencimiento:</label>
        <input type="date" name="fecha_vencimiento" class="form-control" value="<?= $producto['fecha_vencimiento'] ?>">

        <label>Categoría:</label>
        <select name="id_categoria" class="form-select" required>
            <option value="">-- Categoría --</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($producto['id_categoria'] == $c['id']) ? 'selected' : '' ?>>
                    <?= $c['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Imagen de Producto:</label>
        <input type="file" name="imagen" class="form-control">
        <div class="mb-3">
            <img src="../<?= $producto['imagen'] ?>" width="100">
        </div>

        <button type="submit" class="btn btn-success w-100 mb-2">Actualizar</button>
        <a href="productos_gestionar.php" class="btn btn-secondary w-100">Cancelar</a>
    </form>
</div>
</body>
</html>
