<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Micromercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://img.freepik.com/foto-gratis/disposicion-carros-compra-viernes-negro-espacio-copia_23-2148667047.jpg?semt=ais_hybrid&w=740') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        .fondo-opaco {
            background-color: rgba(0, 0, 0, 0.65);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .btn-custom {
            width: 200px;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div class="fondo-opaco">
        <div class="container">
            <h1 class="mb-4">Bienvenido a S&B Market</h1>
            <p class="lead mb-4">Bienvenido a nuestro sistema espero encuentres lo que necesites .</p>
            <p class="lead mb-4">Vendemos variedades de Productos :0</p>
            <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
                <a href="views/login.php" class="btn btn-light btn-custom">Iniciar Sesión</a>
                <a href="views/registro.php" class="btn btn-outline-light btn-custom">Registrarse</a>
            </div>
        </div>
    </div>
</body>

</html>