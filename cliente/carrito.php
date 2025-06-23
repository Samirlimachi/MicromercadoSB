<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
verificarSesion();
verificarRol('cliente');

$busqueda = $_GET['buscar'] ?? '';
// Procesar búsqueda desde el navbar
if (isset($_GET['buscar']) && $_GET['buscar'] !== '') {
    $busqueda = $_GET['buscar'];

    // Buscar coincidencia en categorías
    $stmtCat = $pdo->prepare("SELECT id FROM categorias WHERE nombre LIKE ?");
    $stmtCat->execute(["%$busqueda%"]);
    $categoriaId = $stmtCat->fetchColumn();

    if ($categoriaId) {
        header("Location: categoria.php?id=" . $categoriaId);
        exit();
    } else {
        header("Location: categoria.php?buscar=" . urlencode($busqueda));
        exit();
    }
}

$stmt = $pdo->prepare("SELECT puntos FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$puntosActuales = $stmt->fetchColumn();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = $_SESSION['carrito'];
$total = 0;
foreach ($carrito as $item) {
    $total += $item['subtotal'];
}

$usarPuntos = isset($_POST['usar_puntos']);
$descuento = 0;

if ($usarPuntos && $puntosActuales >= 10) {
    $descuento = floor($puntosActuales / 10);
    $total -= $descuento;
    if ($total < 0) $total = 0;
}

if (isset($_POST['confirmar'])) {
    if (count($carrito) === 0) {
        $mensaje = "⚠️ Tu carrito está vacío.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO ventas (id_usuario, total) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $idVenta = $pdo->lastInsertId();

        foreach ($carrito as $item) {
            $stmt = $pdo->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
            $stmt->execute([$idVenta, $item['id'], $item['cantidad'], $item['subtotal']]);

            $pdo->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?")
                ->execute([$item['cantidad'], $item['id']]);
        }

        $puntosGanados = floor($total / 10);
        $puntosUsados = $usarPuntos ? ($descuento * 10) : 0;

        $pdo->prepare("UPDATE usuarios SET puntos = puntos + ? - ? WHERE id = ?")
            ->execute([$puntosGanados, $puntosUsados, $_SESSION['user_id']]);

        $_SESSION['carrito'] = [];

        $mensaje = "✅ ¡Compra realizada correctamente!<br>Has ganado <strong>$puntosGanados punto(s)</strong>.";
        if ($usarPuntos && $puntosUsados > 0) {
            $mensaje .= "<br>Se usaron <strong>$puntosUsados punto(s)</strong> para obtener un descuento de <strong>Bs $descuento </strong>.";
        }
    }
}

if (isset($_GET['eliminar'])) {
    $indice = $_GET['eliminar'];
    unset($_SESSION['carrito'][$indice]);
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI';
        }

        .carrito-box {
            background: #ddd;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .btn-danger {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            font-weight: bold;
        }

        .btn-pagar {
            background-color: red;
            color: white;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            border: none;
            padding: 15px;
        }

        .btn-pagar:hover {
            background-color: darkred;
        }

        .vaciar-carrito {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .vaciar-carrito:hover {
            text-decoration: underline;
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
            min-width: 200px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 999;
        }

        .submenu a {
            display: block;
            padding: 10px 15px;
            color: black;
            text-decoration: none;
        }

        .submenu a:hover {
            background-color: #f0f0f0;
        }

        .category-item:hover .submenu {
            display: block;
        }

        .category-item>a {
            color: white;
            text-decoration: none;
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

    <div class="container mt-4">
        <h2 class="mb-4">🛒 Tu carrito</h2>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success mt-3"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if (count($carrito) > 0): ?>
            <?php foreach ($carrito as $i => $item): ?>
                <div class="carrito-box d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-4">
                        <img src="../<?= $item['imagen'] ?>" width="100" height="100">
                        <div>
                            <strong><?= htmlspecialchars($item['nombre']) ?></strong><br>
                            Precio: Bs<?= number_format($item['precio'], 2, ',', '.') ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <p><strong>Cantidad:</strong> <?= $item['cantidad'] ?></p>
                        <p><strong>Bs.<?= number_format($item['subtotal'], 2, ',', '.') ?></strong></p>
                        <a href="carrito.php?eliminar=<?= $i ?>" class="btn btn-danger">x</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-end mb-4">
                <a href="carrito.php?vaciar=1" class="vaciar-carrito">🗑 Vaciar carrito</a>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 offset-md-6">
                    <table class="table">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">Bs.<?= number_format($total + $descuento, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Descuento:</strong></td>
                            <td class="text-end">-Bs.<?= number_format($descuento, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="text-end">Bs.<?= number_format($total, 2, ',', '.') ?></td>
                        </tr>
                    </table>

                    <form method="POST">
                        <?php if ($puntosActuales >= 10): ?>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="usar_puntos" id="usarPuntos" <?= $usarPuntos ? 'checked' : '' ?>>
                                <label class="form-check-label" for="usarPuntos">
                                    Usar <?= $puntosActuales ?> punto(s) (<?= floor($puntosActuales / 10) ?> Bs de descuento)
                                </label>
                            </div>
                        <?php else: ?>
                            <div class="text-muted mb-3">
                                (Tienes <?= $puntosActuales ?> punto(s). Se requieren mínimo 10 para canjear descuentos.)
                            </div>
                        <?php endif; ?>
                        <button name="confirmar" class="btn btn-pagar">🛒 Pagar</button>
                    </form>
                </div>
            </div>

            <p class="text-center"><a href="productos_ver.php">Seguir comprando &gt;</a></p>
        <?php else: ?>
            <div class="text-center py-5" style="background-color: #ddd; border-radius: 10px;">
                <h4 class="mb-4 fw-bold">Tu carrito esta vacío</h4>
                <a href="productos_ver.php" class="btn fw-bold" style="background-color: orange; font-size: 1.5rem; padding: 12px 24px;">Continuar Comprando</a>
            </div>
        <?php endif; ?>

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