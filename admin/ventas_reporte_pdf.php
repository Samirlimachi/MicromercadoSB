<?php
require_once '../includes/auth.php';
verificarSesion();
verificarRol('admin');
require_once '../includes/db.php';
require_once '../includes/fpdf/fpdf.php';

// Obtener ventas
$stmt = $pdo->query("
    SELECT v.*, u.nombre AS cliente 
    FROM ventas v 
    LEFT JOIN usuarios u ON v.id_usuario = u.id 
    ORDER BY v.fecha DESC
");
$ventas = $stmt->fetchAll();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Ventas - Micromercado',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,10,'ID',1);
$pdf->Cell(50,10,'Cliente',1);
$pdf->Cell(40,10,'Fecha',1);
$pdf->Cell(40,10,'Total (Bs)',1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);
foreach ($ventas as $v) {
    $pdf->Cell(20,10, $v['id'],1);
    $pdf->Cell(50,10, utf8_decode($v['cliente'] ?? 'Anónimo'),1);
    $pdf->Cell(40,10, $v['fecha'],1);
    $pdf->Cell(40,10, number_format($v['total'], 2, ',', '.'),1);
    $pdf->Ln();
}

$pdf->Output('I', 'reporte_ventas.pdf');
// La línea anterior genera el PDF y lo envía al navegador para que el usuario lo descargue o visualice directamente.

