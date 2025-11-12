<?php
include("../db/conexion.php");
require("../img/fpdf/fpdf.php"); // ✅ Ruta correcta según tu estructura

$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'mensual';
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : date('Y-m');
$graficoImg = isset($_POST['graficoImg']) ? $_POST['graficoImg'] : null;

// Consultar datos agrupados por localidad
$sql = "SELECT localidad, SUM(total) AS total 
        FROM estadisticas 
        WHERE tipo='$tipo' AND periodo='$periodo'
        GROUP BY localidad 
        ORDER BY total DESC";

$result = $conex->query($sql);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode("🏙️ Estadísticas por Localidad"), 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, utf8_decode("Tipo: " . ucfirst($tipo) . " | Período: " . $periodo), 0, 1, 'C');
$pdf->Ln(8);

// Insertar el gráfico si existe
if ($graficoImg) {
    $imgData = str_replace('data:image/png;base64,', '', $graficoImg);
    $imgData = base64_decode($imgData);
    $tmpFile = tempnam(sys_get_temp_dir(), 'grafico_') . '.png';
    file_put_contents($tmpFile, $imgData);
    $pdf->Image($tmpFile, 25, $pdf->GetY(), 160);
    unlink($tmpFile);
    $pdf->Ln(95);
}

// Encabezado de tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(100, 149, 237); // Celeste suave
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(100, 10, utf8_decode("Localidad"), 1, 0, 'C', true);
$pdf->Cell(50, 10, utf8_decode("Total"), 1, 1, 'C', true);

// Datos de la tabla
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235, 245, 255);
$fill = false;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(100, 10, utf8_decode($row['localidad']), 1, 0, 'C', $fill);
        $pdf->Cell(50, 10, $row['total'], 1, 1, 'C', $fill);
        $fill = !$fill;
    }
} else {
    $pdf->Cell(150, 10, utf8_decode("No hay datos disponibles para este período."), 1, 1, 'C');
}

// Pie
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 8, utf8_decode("Generado el " . date("d/m/Y H:i")), 0, 1, 'C');

// Descargar PDF
$pdf->Output("D", "Estadisticas_Localidades_{$tipo}_{$periodo}.pdf");
exit;
?>
