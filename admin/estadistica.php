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

// Tipo de estadística a mostrar: semanal, mensual o anual
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'mensual';

// Determinar el período por defecto según tipo
switch ($tipo) {
    case 'semanal':
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('o-\WW');
        break;
    case 'anual':
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('Y');
        break;
    default:
        $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : date('Y-m');
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
    <link rel="stylesheet" href="../css/estadistica.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Contenedor general para que el menú quede arriba -->
    <main class="container">

        <h2>📅 Estadísticas <?php echo ucfirst($tipo); ?> (<?php echo $periodo; ?>)</h2>

        <!-- 🔹 Botones de tipo -->
        <div class="btn-group">
            <a href="?tipo=semanal"><button>Semanal</button></a>
            <a href="?tipo=mensual"><button>Mensual</button></a>
            <a href="?tipo=anual"><button>Anual</button></a>
            <a href="estadistica_localidad.php"><button class="btn-secundario">🏙️ Por Localidad</button></a>
            <a href="estadistica_comparativa.php"><button class="btn-secundario">Comparativa</button></a>
        </div>
      <!-- 🔸 Nuevo botón -->
    <form id="formPDF" action="generar_pdf_estadisticas.php" method="POST" style="display:inline;">
        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
        <input type="hidden" name="periodo" value="<?php echo $periodo; ?>">
        <input type="hidden" id="graficoImg" name="graficoImg">
        <button type="submit" class="btn-secundario" 
                style="background:#f48fb1;color:white;border:none;padding:8px 15px;border-radius:6px;cursor:pointer;">
            ⬇️ Descargar PDF
        </button>
    </form>
        <!-- 🔹 Botón de actualización -->
        <div class="btn-refresh">
            <button onclick="actualizar()">🔄 Actualizar estadísticas</button>
        </div>

        <!-- 🔹 Gráfico -->
        <div class="chart-container">
            <canvas id="grafico" width="800" height="400"></canvas>
        </div>
    </main>

    <script>
        // Función para actualizar datos
        function actualizar() {
            fetch('generar_estadisticas.php')
                .then(r => r.text())
                .then(t => {
                    alert(t);
                    location.reload();
                })
                .catch(e => alert('Error al actualizar: ' + e));
        }

        // Renderizar gráfico
        const ctx = document.getElementById('grafico');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Cantidad de estudios',
                    data: <?php echo json_encode($values); ?>,
                    backgroundColor: 'rgba(37, 99, 235, 0.6)',
                    borderRadius: 6
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
    <script>
document.getElementById('formPDF').addEventListener('submit', function(e) {
    const canvas = document.getElementById('grafico');
    const imgData = canvas.toDataURL('image/png'); // convierte el gráfico en imagen
    document.getElementById('graficoImg').value = imgData; // la guarda en el input oculto
});
</script>

</body>
</html>
