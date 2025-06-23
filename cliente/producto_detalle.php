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

while ($row = $stmt->fetch()) {
    $productosPorCategoria[$row['categoria']][] = $row;
}


$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: productos_ver.php");
    exit();
}

$stmt = $pdo->prepare("SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON p.id_categoria = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    echo "<h3>Producto no encontrado</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($producto['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI';
        }

        /* NAVBAR estilo rojo */
.navbar {
    background-color: #e60000;
}
.navbar .nav-link,
.navbar-brand {
    color: white !important;
}

/* Categorías principales */
.category-bar {
    background-color: #000;
    font-weight: bold;
    position: relative;
    padding: 10px 0;
}

/* Elemento individual de categoría */
.category-item {
    position: relative;
    padding: 0 15px;
    cursor: pointer;
}

/* Submenú oculto por defecto */
.submenu {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: white;
    min-width: 200px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    display: none;
    z-index: 999;
}

/* Estilo de los enlaces dentro del submenú */
.submenu a {
    display: block;
    padding: 10px 15px;
    color: black;
    text-decoration: none;
    font-weight: normal;
}

/* Hover del submenú */
.submenu a:hover {
    background-color: #f0f0f0;
}

/* Mostrar el submenú al pasar el mouse */
.category-item:hover .submenu {
    display: block;
}

/* Evita que se subraye los enlaces principales */
.category-item > a {
    color: white;
    text-decoration: none;
}

        .product-image {
            max-height: 400px;
            object-fit: contain;
        }

        .btn-green {
            background-color: #e60000;
            color: white;
            font-weight: bold;
            border: none;
        }

        .btn-green:hover {
            background-color: #e60000;
        }

        .btn-red {
            background-color: #e60000;
            color: white;
            font-weight: bold;
            border: none;
        }

        .btn-red:hover {
            background-color: #c40000;
        }


        .category-item {
            cursor: pointer;
            font-weight: bold;
        }

        .submenu {
            display: none;
            top: 100%;
            left: 0;
            min-width: 180px;
            z-index: 1000;
        }

        .category-item:hover .submenu {
            display: block;
        }

        .submenu a:hover {
            background-color: #f0f0f0;
        }


        .social-icons img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: white;
            padding: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        .social-icons img:hover {
            transform: scale(1.1);
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



    <div class="container mt-4">
        <div class="row">
            <div class="col-md-5 text-center">
                <img src="../<?= $producto['imagen'] ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="img-fluid product-image">
            </div>
            <div class="col-md-7">
                <h2 class="text-danger fw-bold"><?= htmlspecialchars($producto['nombre']) ?></h2>

                <!-- Descripción debajo del nombre -->
                <?php if (!empty($producto['descripcion'])): ?>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
                <?php endif; ?>

                <p class="fs-5">Ahora: <strong>Bs.<?= number_format($producto['precio'], 2, ',', '.') ?></strong></p>
                <p>Unidad de Venta: <strong>Unidad</strong></p>
                <p>En stock: <strong><?= $producto['cantidad'] ?></strong></p>
                <p>Categoría: <strong><?= htmlspecialchars($producto['categoria']) ?></strong></p>

                <form method="POST" action="agregar_carrito.php" class="mb-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Cantidad:</span>
                        <input type="number" name="cantidad" min="1" max="<?= $producto['cantidad'] ?>" value="1" class="form-control">
                    </div>
                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                    <input type="hidden" name="nombre" value="<?= $producto['nombre'] ?>">
                    <input type="hidden" name="precio" value="<?= $producto['precio'] ?>">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-green flex-fill">Agregar al Carrito</button>

                    </div>
                </form>

                <!-- Botón para abrir el modal -->
                <p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalImagen">🔍 Ampliar Imagen</button>
                </p>


                <div class="mt-4">
                    <strong>Compartir en:</strong>
                    <div class="d-flex gap-3 mt-3 social-icons">
                        <a href=""><img src="https://img.freepik.com/psd-premium/icon-3d-redes-sociales-facebook_466778-4384.jpg?semt=ais_hybrid&w=740" alt="Facebook" title="Facebook"></a>
                        <a href="#"><img src="https://static.vecteezy.com/system/resources/previews/042/127/160/non_2x/instagram-logo-on-circle-style-with-transparent-background-free-png.png" alt="Instagram" title="Instagram"></a>
                        <a href="#"><img src="https://img.freepik.com/vector-gratis/nuevo-diseno-icono-x-logotipo-twitter-2023_1017-45418.jpg?semt=ais_hybrid&w=740" alt="X" title="X (Twitter)"></a>
                        <a href="#"><img src="https://1000marcas.net/wp-content/uploads/2019/12/Tiktok-Logo-2016.png" alt="TikTok" title="TikTok"></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de imagen ampliada -->
    <div class="modal fade" id="modalImagen" tabindex="-1" aria-labelledby="modalImagenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center p-0">
                    <img src="../<?= $producto['imagen'] ?>" class="img-fluid rounded shadow" alt="Imagen ampliada">
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