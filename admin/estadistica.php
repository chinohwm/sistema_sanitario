<?php
include("../db/conexion.php");
session_start();

if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Tipo de estadística a mostrar: semanal, mensual o anual
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'mensual';

// Determinar el período por defecto según tipo
switch ($tipo) {
    case 'semanal':
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('o-\WW'); // semana actual
        break;
    case 'anual':
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('Y'); // año actual
        break;
    default:
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('Y-m'); // mes actual
        break;
}

// Traer datos de la tabla estadisticas
$query = "SELECT tipo_estudio, total FROM estadisticas WHERE tipo='$tipo' AND periodo='$periodo'";
$result = $conex->query($query);

$labels = [];
$values = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = ucfirst($row['tipo_estudio']);
        $values[] = $row['total'];
    }
} else {
    $labels = ["Sin datos"];
    $values = [0];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>📊 Estadísticas de Salud</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { font-family: Arial; text-align: center; margin: 30px; }
button {
    background-color: #007bff; color: white; border: none;
    padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 16px;
    margin: 5px;
}
button:hover { background-color: #0056b3; }
a { text-decoration: none; }
</style>
</head>
<body>
<h2>📅 Estadísticas <?php echo ucfirst($tipo); ?> (<?php echo $periodo; ?>)</h2>

<!-- Botones para cambiar tipo -->
<a href="?tipo=semanal"><button>Semanal</button></a>
<a href="?tipo=mensual"><button>Mensual</button></a>
<a href="?tipo=anual"><button>Anual</button></a>

<br><br>

<!-- Botón para actualizar -->
<button onclick="actualizar()">🔄 Actualizar estadísticas</button>

<br><br>
<canvas id="grafico" width="800" height="400"></canvas>

<script>
// Función que llama al script de actualización
function actualizar() {
    fetch('generar_estadisticas.php')
        .then(r => r.text())
        .then(t => {
            alert(t);
            location.reload();
        })
        .catch(e => alert('Error al actualizar: ' + e));
}

// Renderizar gráfico con Chart.js
const ctx = document.getElementById('grafico');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Cantidad de estudios',
            data: <?php echo json_encode($values); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    },
    options: {
        scales: { y: { beginAtZero: true } }
    }
});
</script>
</body>
</html>
