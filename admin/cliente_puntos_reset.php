<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE usuarios SET puntos = 0 WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: clientes_gestionar.php");
exit();
