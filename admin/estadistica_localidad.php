<?php
include("../db/conexion.php");
session_start();

if (!isset($_SESSION['id_cargo']) || !isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_cargo = $_SESSION['id_cargo'];
if ($id_cargo == 1) {
    include("../layouts/nav_admin.html");
} elseif ($id_cargo == 2) {
    include("../layouts/nav_promotor.html");
}

// Tipo de estadística
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'mensual';

// Período actual
switch ($tipo) {
    case 'semanal': $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('o-\WW'); break;
    case 'anual':   $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('Y'); break;
    default:        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('Y-m'); break;
}

// Consulta agrupada por localidad
$query = "SELECT localidad, SUM(total) AS total 
          FROM estadisticas 
          WHERE tipo='$tipo' AND periodo='$periodo'
          GROUP BY localidad 
          ORDER BY total DESC";

$result = $conex->query($query);
$labels = [];
$values = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['localidad'];
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
    <title>🏙️ Estadísticas por Localidad</title>
    <link rel="stylesheet" href="../css/estadistica.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <main class="container">
        <h2>🏙️ Operativos por Localidad – <?php echo ucfirst($tipo); ?> (<?php echo $periodo; ?>)</h2>

        <div class="btn-group">
            <a href="?tipo=semanal"><button>Semanal</button></a>
            <a href="?tipo=mensual"><button>Mensual</button></a>
            <a href="?tipo=anual"><button>Anual</button></a>
            <a href="estadistica.php"><button class="btn-secundario">📊 Por Estudio</button></a>
        </div>
        <!-- 🔸 Nuevo botón -->
<form id="formPDF" action="generar_pdf_localidades.php" method="POST" style="display:inline;">
    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
    <input type="hidden" name="periodo" value="<?php echo $periodo; ?>">
    <input type="hidden" id="graficoImg" name="graficoImg">
    <button type="submit" class="btn-secundario"
            style="background:#f48fb1;color:white;border:none;padding:8px 15px;border-radius:6px;cursor:pointer;">
        ⬇️ Descargar PDF
    </button>
</form>

        <div class="btn-refresh">
            <button onclick="actualizar()">🔄 Actualizar estadísticas</button>
        </div>

        <div class="chart-container">
            <canvas id="grafico_localidad" width="800" height="400"></canvas>
        </div>
    </main>

    <script>
        // Función de actualización
        function actualizar() {
            fetch('generar_estadisticas_localidad.php')
                .then(r => r.text())
                .then(t => { alert(t); location.reload(); })
                .catch(e => alert('Error al actualizar: ' + e));
        }

        // Gráfico de barras vertical
        const ctx = document.getElementById('grafico_localidad');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Cantidad de operativos por localidad',
                    data: <?php echo json_encode($values); ?>,
                    backgroundColor: 'rgba(37, 99, 235, 0.6)',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#1e3a8a', font: { size: 13 } } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#1e3a8a' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#1e3a8a' },
                        grid: { color: '#f3f4f6' }
                    }
                }
            }
        });
    </script>
    <script>
document.getElementById('formPDF').addEventListener('submit', function(e) {
    const canvas = document.getElementById('grafico_localidad');
    const imgData = canvas.toDataURL('image/png'); // convierte el gráfico en imagen
    document.getElementById('graficoImg').value = imgData; // la guarda en el input oculto
});
</script>

</body>
</html>
