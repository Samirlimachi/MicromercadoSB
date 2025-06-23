<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;

// Evitar que el admin se elimine a sí mismo
if ($id && $_SESSION['user_id'] != $id) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: usuarios_gestionar.php");
exit();
    