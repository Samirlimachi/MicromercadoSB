<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
verificarSesion();
verificarRol('cliente');

// Buscar producto

$busqueda = $_GET['buscar'] ?? '';
// if ($busqueda) {
//     $stmt = $pdo->prepare("SELECT * FROM productos WHERE cantidad > 0 AND nombre LIKE ?");
//     $stmt->execute(["%$busqueda%"]);
// } else {
//     $stmt = $pdo->query("SELECT * FROM productos WHERE cantidad > 0");
// }
// $productosPorCategoria = [];

if ($busqueda) {
    $stmt = $pdo->prepare("SELECT p.*, c.nombre AS categoria FROM productos p 
        JOIN categorias c ON p.id_categoria = c.id 
        WHERE p.cantidad > 0 
        AND (p.nombre LIKE ? OR c.nombre LIKE ?)");
    $stmt->execute(["%$busqueda%", "%$busqueda%"]);
} else {
    $stmt = $pdo->query("SELECT p.*, c.nombre AS categoria FROM productos p 
        JOIN categorias c ON p.id_categoria = c.id 
        WHERE p.cantidad > 0");
}

$productosPorCategoria = [];

while ($row = $stmt->fetch()) {
    $productosPorCategoria[$row['categoria']][] = $row;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tienda - Micromercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
        }

        .navbar {
            background-color: #e60000;
        }

        .navbar .nav-link,
        .navbar-brand {
            color: white !important;
        }

        .product-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
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

        .ver-mas-btn {
            background-color: #e60000;
            color: white;
            font-weight: bold;
        }

        .ver-mas-btn:hover {
            background-color: #c10000;
        }

        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }

        .category-icon {
            width: 170px;
            height: 170px;
            object-fit: cover;
            border: 4px solid transparent;
            transition: transform 0.3s ease;
        }

        .category-icon:hover {
            transform: scale(1.1);
        }

        .card-title {
            font-size: 0.95rem;
            height: 40px;
            overflow: hidden;
        }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav class="navbar navbar-expand-lg px-4">
        <a class="navbar-brand text-white fw-bold" href="productos_ver.php">S&B Market</a>

        <!-- Buscador en el centro -->
        <form class="d-flex mx-auto" method="GET">
            <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar producto..." value="<?= htmlspecialchars($busqueda) ?>" style="width: 300px;">
            <button class="btn btn-light" type="submit">🔍</button>
        </form>

        <!-- Iconos al lado derecho -->
        <div class="ms-auto d-flex gap-3">
            <a href="carrito.php" class="nav-link text-white">🛒 Carrito</a>


            <a href="../views/logout.php" class="nav-link text-white">🔓 Salir</a>

        </div>
    </nav>

    <!-- CATEGORÍAS CON SUBMENÚS ---------------------------------------------------------------------->
    <!-- Primero Revisas como Administrador el ID de la Categoria producto luego modificas -->
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


    <!-- CARRUSEL -->
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://amarket.com.bo/cdn/shop/files/Portada_Macrocategoria_-_Licores_Vapes_y_Carnes_c3dbe6f2-0e30-4003-8237-6e9d32449408_1350x380.png?v=1742828472" class="d-block w-100" alt="Promo 1">
            </div>
            <div class="carousel-item">
                <img src="https://amarket.com.bo/cdn/shop/collections/Banner_Comida_Fresca_3380x900px_1200x600_crop_center.png?v=1742395613" class="d-block w-100" alt="Promo 2">
            </div>
            <div class="carousel-item">
                <img src="https://amarket.com.bo/cdn/shop/files/Portada_Macrocategoria_-_Menaje_de_Cocina_2bafe340-27ec-4c7c-8823-ca148bdc4f1d_1350x380.png?v=1742828848" class="d-block w-100" alt="Promo 3">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- CATEGORÍAS REDONDAS -->
    <div class="container my-5">
        <div class="row justify-content-center text-center gap-4">


            <div class="col-auto">
                <a href="categoria.php?id=1" class="text-decoration-none text-dark">
                    <img src="https://amarket.com.bo/cdn/shop/files/panes_04286ef5-a080-4f8e-8e37-8a3db3602645_208x208.png?v=1742827783" class="rounded-circle category-icon" alt="Panadería">
                    <p class="fw-bold mt-2">Panadería</p>
                </a>
            </div>




            <div class="col-auto">
                <a href="categoria.php?id=2" class="text-decoration-none text-dark">
                    <img src="https://amarket.com.bo/cdn/shop/files/frutas_54ffa557-aada-4a8e-afce-67d3789848b2_208x208.png?v=1742827729" class="rounded-circle category-icon" alt="Frutas">
                    <p class="fw-bold mt-2">Frutas y Verduras</p>
                </a>
            </div>




            <div class="col-auto">
                <a href="categoria.php?id=3" class="text-decoration-none text-dark">
                    <img src="https://amarket.com.bo/cdn/shop/files/carnes_ddafcba5-67f4-4468-97d0-1ead1577dec8_208x208.png?v=1742827742" class="rounded-circle category-icon" alt="Carnes">
                    <p class="fw-bold mt-2">Carnes</p>
                </a>
            </div>




            <div class="col-auto">
                <a href="categoria.php?id=4" class="text-decoration-none text-dark">
                    <img src="https://amarket.com.bo/cdn/shop/files/lacteos_ee8ff0e8-f806-4378-9774-93fe85059c77_208x208.png?v=1742827764" class="rounded-circle category-icon" alt="Lácteos">
                    <p class="fw-bold mt-2">Lácteos</p>
                </a>
            </div>


            <div class="col-auto">
                <a href="categoria.php?id=5" class="text-decoration-none text-dark">
                    <img src="https://amarket.com.bo/cdn/shop/files/despensa_19728ec1-761e-4c0d-8daf-377d878e9afd_208x208.png?v=1742827818" class="rounded-circle category-icon" alt="Despensa">
                    <p class="fw-bold mt-2">Despensa</p>
                </a>
            </div>



            <div class="col-auto">
                <a href="categoria.php?id=6" class="text-decoration-none text-dark">
                    <img src="https://amarket.com.bo/cdn/shop/files/drinks_9e00bba2-f310-4f2b-8a8b-8a225f8444c5_208x208.png?v=1742827840" class="rounded-circle category-icon" alt="Bebidas">
                    <p class="fw-bold mt-2">Bebidas</p>
                </a>
            </div>


        </div>
    </div>


    <!-- PRODUCTOS -->
    <div class="container mt-4">
        <?php if ($busqueda): ?>
            <p>🔍 Resultados para: <strong><?= htmlspecialchars($busqueda) ?></strong></p>
        <?php endif; ?>

        <?php if (empty($productosPorCategoria)): ?>
            <div class="alert alert-warning">⚠️ No se encontraron productos que coincidan con tu búsqueda.</div>
        <?php endif; ?>

        <?php foreach ($productosPorCategoria as $categoria => $productos): ?>
            <h4 class="fw-bold mt-5"><?= htmlspecialchars($categoria) ?></h4>
            <div class="row">
                <?php foreach ($productos as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="producto_detalle.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
                                <div class="p-3 text-center">
                                    <img src="../<?= $p['imagen'] ?>" class="img-fluid" style="height: 160px; object-fit: contain;" alt="<?= $p['nombre'] ?>">
                                </div>
                            </a>
                            <div class="card-body text-center">
                                <h6 class="card-title">
                                    <a href="producto_detalle.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </a>
                                </h6>

                                <h5 class="text-danger fw-bold">Bs <?= number_format($p['precio'], 2, ',', '.') ?></h5>


                                <p class="text-muted">Cantidad disponible: <?= $p['cantidad'] ?></p>

                                <form method="POST" action="agregar_carrito.php">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="nombre" value="<?= $p['nombre'] ?>">
                                    <input type="hidden" name="precio" value="<?= $p['precio'] ?>">

                                    <label for="cantidad-<?= $p['id'] ?>" class="form-label text-start w-100">Cantidad</label>
                                    <input id="cantidad-<?= $p['id'] ?>" type="number" name="cantidad" min="1" max="<?= $p['cantidad'] ?>" value="1" class="form-control mb-2" required>

                                    <button type="submit" class="btn btn-danger w-100 fw-bold">Agregar</button>
                                </form>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
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