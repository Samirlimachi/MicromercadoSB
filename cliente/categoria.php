<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
verificarSesion();
verificarRol('cliente');

// Buscar por texto si se usó el buscador
$busqueda = $_GET['buscar'] ?? '';
$productos = [];

if ($busqueda) {
    // Buscar coincidencia con categoría
    $stmtCat = $pdo->prepare("SELECT id FROM categorias WHERE nombre LIKE ?");
    $stmtCat->execute(["%$busqueda%"]);
    
    $categoriaCoincidente = $stmtCat->fetchColumn();
    if ($categoriaCoincidente) {
        header("Location: categoria.php?id=" . $categoriaCoincidente);
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE nombre LIKE ? AND cantidad > 0");
        $stmt->execute(["%$busqueda%"]);
        $productos = $stmt->fetchAll();
    }
} else {
    // Continuar si se accede directamente por ID
    $categoriaId = $_GET['id'] ?? null;
    if (!$categoriaId) {
        header("Location: productos_ver.php");
        exit();
    }

    // Obtener nombre de la categoría
    $stmt = $pdo->prepare("SELECT nombre FROM categorias WHERE id = ?");
    $stmt->execute([$categoriaId]);
    $categoriaNombre = $stmt->fetchColumn();
    if (!$categoriaNombre) {
        echo "<h3>Categoria no encontrada.</h3>";
        exit();
    }
    

    // Ordenamiento
    $orden = $_GET['orden'] ?? 'nombre';
    switch ($orden) {
        case 'precio': $orderBy = 'precio ASC'; break;
        case 'masvendido': $orderBy = 'cantidad DESC'; break;
        default: $orderBy = 'nombre ASC'; break;
    }

    // Obtener productos de la categoría
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id_categoria = ? AND cantidad > 0 ORDER BY $orderBy");
    $stmt->execute([$categoriaId]);
    $productos = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoriaNombre) ?> - Catálogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI';
        }

        .sidebar {
            background: #f4f0ef;
            padding: 20px;
            border-radius: 8px;
        }

        .product-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .product-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 8px;
            min-height: 40px;
        }

        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #e60000;
            margin-bottom: 10px;
        }

        .btn-agregar {
            background: #e60000;
            border: none;
            font-weight: bold;
        }

        .btn-agregar:hover {
            background: #c40000;
        }

        .navbar {
            background-color: #e60000;
        }

        .navbar .nav-link,
        .navbar-brand {
            color: white !important;
        }

        .category-bar {
            background-color: #000;
            font-weight: bold;
            position: relative;
            padding: 10px 0;
        }

        .category-item {
            position: relative;
            padding: 0 15px;
            cursor: pointer;
        }

        .submenu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            min-width: 150px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 999;
        }

        .submenu a {
            display: block;
            padding: 10px;
            color: black;
            text-decoration: none;
        }

        .submenu a:hover {
            background-color: #f0f0f0;
        }

        .category-item:hover .submenu {
            display: block;
        }

        .list-unstyled a {
            display: block;
            padding: 8px 12px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
            text-decoration: none;
            margin-bottom: 8px;
            transition: background 0.2s;
        }

        .list-unstyled a:hover {
            background: #e60000;
            color: white;
            border-color: #e60000;
        }

        .product-title a {
            color: #000;
            text-decoration: none;
        }

        .product-title a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg px-4">
        <a class="navbar-brand text-white fw-bold" href="productos_ver.php">S&B Market</a>
        <form class="d-flex mx-auto" method="GET">
            <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>" style="width: 300px;">
            <button class="btn btn-light" type="submit">🔍</button>
        </form>
        <div class="ms-auto d-flex gap-3">
            <a href="carrito.php" class="nav-link text-white">🛒 Carrito</a>
            <a href="../views/logout.php" class="nav-link text-white">🔓 Salir</a>
        </div>
    </nav>

    <div class="category-bar d-flex justify-content-center gap-5">
        <div class="category-item text-white">
            Ofertas
            <div class="submenu">
                <a href="categoria.php?id=1">Super Ofertas</a>
            </div>
        </div>
        <div class="category-item text-white">
            <a href="categoria.php?id=2" class="text-white text-decoration-none">Lo Nuevo</a>
        </div>
        <div class="category-item text-white">
            Abarrotes y Despensas
            <div class="submenu">
                <a href="categoria.php?id=3">Fideos y Pastas</a>
                <a href="categoria.php?id=3">Arroz Legumbres y Semillas</a>
                <a href="categoria.php?id=3">Aceites y Condimentos</a>
                <a href="categoria.php?id=3">Salsas y Aderezos</a>
            </div>
        </div>
        <div class="category-item text-white">
            Lácteos y Derivadas
            <div class="submenu">
                <a href="categoria.php?id=4">Leches y Yogures</a>
                <a href="categoria.php?id=4">Quesos y Mantequillas</a>
                <a href="categoria.php?id=4">Helados y Postres Lácteos</a>
            </div>
        </div>
        <div class="category-item text-white">
            Bebidas y Licores
            <div class="submenu">
                <a href="categoria.php?id=4">Cerveza y Vinos</a>
                <a href="categoria.php?id=4">Licores y Destilados</a>
                <a href="categoria.php?id=4">Bebidas Sin Alcohol</a>
            </div>
        </div>
        <div class="category-item text-white">
            Frutas y Verduras
            <div class="submenu">
                <a href="categoria.php?id=5">Frutas Frescas</a>
                <a href="categoria.php?id=5">Verduras y Hortalizas</a>
                <a href="categoria.php?id=5">Hierbas y Especias Frescas</a>
            </div>
        </div>
        <div class="category-item text-white">
            Snacks y Confiterías
            <div class="submenu">
                <a href="categoria.php?id=6">Chocolates y Dulces</a>
                <a href="categoria.php?id=6">Galletas y Pasteles</a>
                <a href="categoria.php?id=6">Chips y Aperitivos</a>
            </div>
        </div>
    </div>

    <!-- ... navbar y categoría bar igual ... -->
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-2">
            <div class="sidebar">
                <p class="filter-title fw-bold">Filtros</p>
                <ul class="list-unstyled">
                    <li><a href="?id=<?= $categoriaId ?? '' ?>&orden=masvendido">🔺 Más Vendidos</a></li>
                    <li><a href="?id=<?= $categoriaId ?? '' ?>&orden=precio">💲 Menor Precio</a></li>
                    <li><a href="?id=<?= $categoriaId ?? '' ?>&orden=nombre">🔤 A-Z</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-10">
            <h3 class="mb-3">Categoría: <?= htmlspecialchars($categoriaNombre ?? 'General') ?></h3>

            <?php if ($busqueda): ?>
                <p>🔍 Resultados para: <strong><?= htmlspecialchars($busqueda) ?></strong></p>
            <?php endif; ?>

            <?php if (empty($productos)): ?>
                <div class="no-results">
                    <img src="https://static6.depositphotos.com/1002188/648/i/450/depositphotos_6489061-stock-photo-sad-symbol.jpg" alt="Sin resultados">
                    <p class="text-muted">Ups! No encontramos resultados para tu búsqueda.</p>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-3 mb-4">
                        <div class="product-card">
                            <a href="producto_detalle.php?id=<?= $producto['id'] ?>">
                                <img src="../<?= $producto['imagen'] ?>" class="product-img" alt="<?= $producto['nombre'] ?>">
                            </a>
                            <p class="product-title">
                                <a href="producto.php?id=<?= $producto['id'] ?>">
                                    <?= htmlspecialchars($producto['nombre']) ?>
                                </a>
                            </p>
                            <p class="product-price">Bs <?= number_format($producto['precio'], 2, ',', '.') ?></p>
                            <form method="POST" action="agregar_carrito.php">
                                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                                <input type="hidden" name="nombre" value="<?= $producto['nombre'] ?>">
                                <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="<?= $producto['cantidad'] ?>" class="form-control mb-2">
                                <button class="btn btn-agregar w-100">Agregar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-light text-dark pt-5 pb-4 mt-5 border-top">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Síguenos</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="text-dark"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-dark"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="text-dark"><i class="bi bi-youtube fs-4"></i></a>
                </div>
            </div>

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">¿Necesitas ayuda?</h5>
                <p><a href="https://wa.link/gqf01b" class="text-dark text-decoration-none">Atención al cliente</a></p>
                <p><a href="https://wa.link/gqf01b" class="text-dark text-decoration-none">Preguntas frecuentes</a></p>
                <p><a href="#" class="text-dark text-decoration-none">Ya Compraste</a></p>
                <p><a href="#" class="text-dark text-decoration-none">Funcion Ticket</a></p>
                
            </div>

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold">Servicios</h5>
                <p><a href="#" class="text-dark text-decoration-none">Sucursales</a></p>
                <p><a href="#" class="text-dark text-decoration-none">Delivery 24 Horas</a></p>
                <p><a href="#" class="text-dark text-decoration-none">Ofertas </a></p>
            </div>

            
        </div>
        <hr class="my-3">
        <div class="text-center">
            <p class="mb-0">Todos los derechos reservados © 2025 Samir Limachi Lopez -Bonny Alberto</p>
            <small class="text-muted">
                <a href="#" class="text-decoration-none text-muted">Política de privacidad</a> |
                <a href="#" class="text-decoration-none text-muted">Términos del servicio</a> |
                <a href="#" class="text-decoration-none text-muted">Política de envío</a>
            </small>
        </div>
    </div>
</footer>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<!-- logos de las redes sociales -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
