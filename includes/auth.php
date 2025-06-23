<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function verificarSesion() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/acceso_denegado.php");
        exit();
    }
}

function verificarRol($rolRequerido) {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $rolRequerido) {
        header("Location: ../views/acceso_denegado.php");
        exit();
    }
}
