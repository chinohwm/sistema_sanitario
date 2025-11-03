<?php
include("../db/conexion.php");

// 🔄 Generar estadísticas actualizadas antes de mostrar
include("../admin/generar_estadisticas.php");

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

// 🔹 Traer los últimos 6 meses de estadísticas globales
$sql = "
SELECT periodo, SUM(total) AS total_general
FROM estadisticas
WHERE tipo = 'mensual'
GROUP BY periodo
ORDER BY periodo ASC
";
$result = $conex->query($sql);

$labels = [];
$values = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['periodo'];
        $values[] = $row['total_general'];
    }
} else {
    $labels = ["Sin datos"];
    $values = [0];
}

// 🔹 Calcular variación respecto al mes anterior
$porcentaje = 0;
if (count($values) >= 2) {
    $ultimo = end($values);
    $anterior = prev($values);
    if ($anterior > 0) {
        $porcentaje = round((($ultimo - $anterior) / $anterior) * 100, 1);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>📈 Comparativa Mensual de Estadísticas</title>
<link rel="stylesheet" href="../css/estadistica.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<main class="container">

    <h2>📈 Comparativa Mensual</h2>
    <p style="font-size:1.1rem; color:#1e3a8a;">
        <?php if ($porcentaje > 0): ?>
            📊 <strong>Aumento de +<?php echo $porcentaje; ?>%</strong> respecto al mes anterior.
        <?php elseif ($porcentaje < 0): ?>
            📉 <strong>Disminución de <?php echo abs($porcentaje); ?>%</strong> respecto al mes anterior.
        <?php else: ?>
            ➖ Sin cambios respecto al mes anterior.
        <?php endif; ?>
    </p>

    <div class="btn-group">
        <a href="estadistica.php"><button>📋 Por Tipo de Estudio</button></a>
        <a href="estadistica_localidad.php"><button>📍 Por Localidad</button></a>
        <a href="estadistica_comparativa.php"><button class="activo">📈 Comparativa Mensual</button></a>
    </div>

    <div class="chart-container">
        <canvas id="graficoComparativo" width="800" height="400"></canvas>
    </div>

    <script>
    const ctx = document.getElementById('graficoComparativo');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Total de estudios mensuales',
                data: <?php echo json_encode($values); ?>,
                borderColor: 'rgba(37,99,235,1)',
                backgroundColor: 'rgba(37,99,235,0.2)',
                borderWidth: 3,
                pointRadius: 5,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                legend: { display: true },
                title: {
                    display: true,
                    text: 'Evolución de los últimos meses',
                    font: { size: 16 }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>

</main>
</body>
</html>
