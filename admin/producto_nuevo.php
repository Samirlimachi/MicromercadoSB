<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $cantidad = intval($_POST['cantidad']);
    $stock_minimo = intval($_POST['stock_minimo']);
    $vencimiento = $_POST['fecha_vencimiento'] ?? null;
    $id_categoria = $_POST['id_categoria'] ?? null;

  
    // Validación de valores numéricos
if ($precio <= 0 || $cantidad < 0 || $stock_minimo <= 0) {
    die("Error: El precio debe ser mayor a cero y no se permiten valores negativos.");
}


    $ruta = null;
    if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen']['name'];
        $tmp = $_FILES['imagen']['tmp_name'];
        $ruta = 'uploads/' . basename($imagen);
        move_uploaded_file($tmp, "../$ruta");
    }

    $stmt = $pdo->prepare("INSERT INTO productos 
        (nombre, descripcion, precio, imagen, cantidad, id_categoria, fecha_vencimiento, stock_minimo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $precio, $ruta, $cantidad, $id_categoria, $vencimiento, $stock_minimo]);

    header("Location: productos_gestionar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://static.vecteezy.com/system/resources/previews/012/872/841/non_2x/abstract-background-with-an-attractive-appearance-and-can-be-used-for-your-background-vector.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-container {
            background-color: rgba(220, 53, 69, 0.9);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 600px;
            margin: 60px auto;
            color: white;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 15px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-registrar {
            background-color: #28a745;
            font-weight: bold;
            border: none;
        }

        .btn-registrar:hover {
            background-color: #218838;
        }

        .btn-cancelar {
            background-color: #6c757d;
            font-weight: bold;
            border: none;
        }

        .btn-cancelar:hover {
            background-color: #5a6268;
        }

        .form-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: black;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-title">REGISTRO DE PRODUCTO</div>
        <form method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" class="form-control"></textarea>
<label for="precio">Precio Bs:</label>
<input type="number" step="0.01" name="precio" class="form-control" required min="0.01">

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" required min="0">

            <label for="stock_minimo">Stock Mínimo:</label>
            <input type="number" name="stock_minimo" class="form-control" required min="0">

            <label for="id_categoria">Categoría:</label>
            <select name="id_categoria" class="form-select" required>
                <option value="">-- Seleccionar Categoría --</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="fecha_vencimiento">Fecha Vencimiento:</label>
            <input type="date" name="fecha_vencimiento" class="form-control">

            <label for="imagen">Imagen de Producto:</label>
            <input type="file" name="imagen" class="form-control bg-warning text-dark">

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-registrar px-4">Registrar</button>
                <a href="productos_gestionar.php" class="btn btn-cancelar px-4">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>
