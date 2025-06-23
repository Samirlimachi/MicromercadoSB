<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
verificarSesion();
verificarRol('cliente');

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];
$subtotal = $precio * $cantidad;

// Obtener la imagen desde la base de datos
$stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
$stmt->execute([$id]);
$imagen = $stmt->fetchColumn(); // Ruta relativa a la imagen (ej. 'imagenes/productos/coca.png')

// Crear item con imagen incluida
$item = [
    'id' => $id,
    'nombre' => $nombre,
    'precio' => $precio,
    'cantidad' => $cantidad,
    'subtotal' => $subtotal,
    'imagen' => $imagen // ✅ clave agregada
];

// Agregar o actualizar en el carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$encontrado = false;
foreach ($_SESSION['carrito'] as &$producto) {
    if ($producto['id'] == $id) {
        $producto['cantidad'] += $cantidad;
        $producto['subtotal'] = $producto['cantidad'] * $producto['precio'];
        $encontrado = true;
        break;
    }
}
unset($producto);


if (!$encontrado) {
    $_SESSION['carrito'][] = $item;
}

header("Location: productos_ver.php");
exit();
