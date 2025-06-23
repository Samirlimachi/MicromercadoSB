<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: productos_gestionar.php");
exit();
