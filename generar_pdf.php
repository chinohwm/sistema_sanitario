<?php
require('fpdf/fpdf.php');

// Conexión a base de datos
$mysqli = new mysqli("localhost", "root", "", "secretaria_salud");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Obtener ID del paciente
$id_paciente = $_POST['id_paciente'] ?? null;
if (!$id_paciente) die("ID de paciente no especificado");

// Obtener datos del paciente
$query = $mysqli->prepare("SELECT * FROM pacientes WHERE id_paciente = ?");
$query->bind_param("i", $id_paciente);
$query->execute();
$result = $query->get_result();
$paciente = $result->fetch_assoc();

if (!$paciente) die("Paciente no encontrado");

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('logo_aire.png', 10, 6, 30); // opcional, asegurate de tener esta imagen
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Ficha de Salud del Paciente', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Mostrar datos
$campos = [
    "Nombre" => $paciente['nombre'],
    "Apellido" => $paciente['apellido'],
    "DNI" => $paciente['dni'],
    "Celular" => $paciente['celular'],
    "Género" => $paciente['genero'],
    "Fecha de nacimiento" => $paciente['fecha_nacimiento'],
    "Correo electrónico" => $paciente['correo_electronico'],
    "Localidad" => $paciente['localidad'],
    "Domicilio" => $paciente['domicilio'],
    "Obra Social" => $paciente['obra_social'],
    "Peso (kg)" => $paciente['peso'],
    "Talla (cm)" => $paciente['talla'],
    "Promotor" => $paciente['promotor']
];

foreach ($campos as $campo => $valor) {
    $pdf->Cell(60, 10, utf8_decode("$campo:"), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($valor), 0, 1);
}

$pdf->Output();
?>
