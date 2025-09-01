<?php
require('../fpdf/fpdf.php');

// Conexión a base de datos
$mysqli = new mysqli("localhost", "root", "", "secretaria_salud");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$id_paciente = $_POST['id_paciente'] ?? null;
if (!$id_paciente) die("Paciente no especificado");

// Obtener datos del paciente
$stmt = $mysqli->prepare("SELECT * FROM pacientes WHERE id_paciente = ?");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();
if (!$paciente) die("Paciente no encontrado");

// Función para obtener un solo resultado
function obtenerDato($mysqli, $tabla, $id_paciente) {
    $sql = "SELECT * FROM $tabla WHERE id_paciente = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

class PDF extends FPDF {
    function Header() {
        if (file_exists('../logo_aire.png')) {
            $this->Image('../logo_aire.png',10,6,20);
        }
        $this->SetFont('Arial','B',16);
        $this->SetTextColor(0, 70, 140);
        $this->Cell(0,10,utf8_decode('Ficha de Salud del Paciente'),0,1,'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
    }

    function recuadro($titulo) {
        $this->SetFillColor(220, 220, 220);
        $this->SetDrawColor(180, 180, 180);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, utf8_decode($titulo), 1, 1, 'L', true);
        $this->Ln(2);
    }

    function celda($label, $value) {
        $this->SetFont('Arial', '', 11);
        $this->Cell(60, 8, utf8_decode($label.':'), 0, 0);
        $this->Cell(0, 8, utf8_decode($value), 0, 1);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Recuadro de datos personales
$pdf->recuadro("Datos del paciente");
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

foreach ($campos as $k => $v) {
    $pdf->celda($k, $v);
}
$pdf->Ln(5);

// Recuadro para cada análisis si existe
function mostrarAnalisis($pdf, $titulo, $datos, $campos) {
    if ($datos) {
        $pdf->recuadro($titulo);
        foreach ($campos as $label => $campoBD) {
            if (!empty($datos[$campoBD])) {
                $pdf->celda($label, $datos[$campoBD]);
            }
        }
        $pdf->Ln(5);
    }
}

// Consultar y mostrar estudios si existen
mostrarAnalisis($pdf, "Sangre Oculta", obtenerDato($mysqli, "sangre_oculta", $id_paciente), [
    "Estado" => "estado",
    "Fecha" => "fecha",
    "Observación" => "observacion"
]);

mostrarAnalisis($pdf, "Glucemia", obtenerDato($mysqli, "glucemia", $id_paciente), [
    "Sede" => "sede",
    "Localidad" => "localidad",
    "Estado" => "estado",
    "Derivación" => "derivacion",
    "Observación" => "observacion",
    "Fecha" => "fecha"
]);

mostrarAnalisis($pdf, "Mamografía", obtenerDato($mysqli, "mamografia", $id_paciente), [
    "Observación" => "observacion",
    "Turno" => "turno"
]);

mostrarAnalisis($pdf, "Sífilis", obtenerDato($mysqli, "sifilis", $id_paciente), [
    "Sífilis" => "sifilis",
    "Derivación" => "derivacion",
    "Observación" => "observacion"
]);

mostrarAnalisis($pdf, "VIH", obtenerDato($mysqli, "vih", $id_paciente), [
    "VIH" => "vih",
    "Derivación" => "derivacion",
    "Observación" => "observacion"
]);

mostrarAnalisis($pdf, "VPH", obtenerDato($mysqli, "vph", $id_paciente), [
    "Estado" => "estado",
    "Fecha" => "fecha",
    "Observación" => "observacion"
]);

$pdf->Output();
