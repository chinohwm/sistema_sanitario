<?php
include("../db/conexion.php");

// OPCIONAL: limpiar tabla para evitar duplicados
$conex->query("TRUNCATE TABLE estadisticas");

// === SEMANAL ===
$conex->query("
INSERT INTO estadisticas (tipo, periodo, tipo_estudio, total)
SELECT 'semanal', DATE_FORMAT(fecha, '%x-W%v'), 'presion', COUNT(*) FROM presion GROUP BY DATE_FORMAT(fecha, '%x-W%v')
UNION ALL
SELECT 'semanal', DATE_FORMAT(fecha, '%x-W%v'), 'glucemia', COUNT(*) FROM glucemia GROUP BY DATE_FORMAT(fecha, '%x-W%v')
UNION ALL
SELECT 'semanal', DATE_FORMAT(fecha, '%x-W%v'), 'sangre_oculta', COUNT(*) FROM sangre_oculta GROUP BY DATE_FORMAT(fecha, '%x-W%v')
UNION ALL
SELECT 'semanal', DATE_FORMAT(fecha, '%x-W%v'), 'sifilis', COUNT(*) FROM sifilis WHERE fecha <> '0000-00-00' GROUP BY DATE_FORMAT(fecha, '%x-W%v')
UNION ALL
SELECT 'semanal', DATE_FORMAT(fecha, '%x-W%v'), 'vih', COUNT(*) FROM vih GROUP BY DATE_FORMAT(fecha, '%x-W%v')
UNION ALL
SELECT 'semanal', DATE_FORMAT(fecha, '%x-W%v'), 'vph', COUNT(*) FROM vph GROUP BY DATE_FORMAT(fecha, '%x-W%v')
UNION ALL
SELECT 'semanal', DATE_FORMAT(fecha_registro, '%x-W%v'), 'auh', COUNT(*) FROM auh GROUP BY DATE_FORMAT(fecha_registro, '%x-W%v');
");

// === MENSUAL ===
$conex->query("
INSERT INTO estadisticas (tipo, periodo, tipo_estudio, total)
SELECT 'mensual', DATE_FORMAT(fecha, '%Y-%m'), 'presion', COUNT(*) FROM presion GROUP BY DATE_FORMAT(fecha, '%Y-%m')
UNION ALL
SELECT 'mensual', DATE_FORMAT(fecha, '%Y-%m'), 'glucemia', COUNT(*) FROM glucemia GROUP BY DATE_FORMAT(fecha, '%Y-%m')
UNION ALL
SELECT 'mensual', DATE_FORMAT(fecha, '%Y-%m'), 'sangre_oculta', COUNT(*) FROM sangre_oculta GROUP BY DATE_FORMAT(fecha, '%Y-%m')
UNION ALL
SELECT 'mensual', DATE_FORMAT(fecha, '%Y-%m'), 'sifilis', COUNT(*) FROM sifilis WHERE fecha <> '0000-00-00' GROUP BY DATE_FORMAT(fecha, '%Y-%m')
UNION ALL
SELECT 'mensual', DATE_FORMAT(fecha, '%Y-%m'), 'vih', COUNT(*) FROM vih GROUP BY DATE_FORMAT(fecha, '%Y-%m')
UNION ALL
SELECT 'mensual', DATE_FORMAT(fecha, '%Y-%m'), 'vph', COUNT(*) FROM vph GROUP BY DATE_FORMAT(fecha, '%Y-%m')
UNION ALL
SELECT 'mensual', DATE_FORMAT(fecha_registro, '%Y-%m'), 'auh', COUNT(*) FROM auh GROUP BY DATE_FORMAT(fecha_registro, '%Y-%m');
");

// === ANUAL ===
$conex->query("
INSERT INTO estadisticas (tipo, periodo, tipo_estudio, total)
SELECT 'anual', YEAR(fecha), 'presion', COUNT(*) FROM presion GROUP BY YEAR(fecha)
UNION ALL
SELECT 'anual', YEAR(fecha), 'glucemia', COUNT(*) FROM glucemia GROUP BY YEAR(fecha)
UNION ALL
SELECT 'anual', YEAR(fecha), 'sangre_oculta', COUNT(*) FROM sangre_oculta GROUP BY YEAR(fecha)
UNION ALL
SELECT 'anual', YEAR(fecha), 'sifilis', COUNT(*) FROM sifilis WHERE fecha <> '0000-00-00' GROUP BY YEAR(fecha)
UNION ALL
SELECT 'anual', YEAR(fecha), 'vih', COUNT(*) FROM vih GROUP BY YEAR(fecha)
UNION ALL
SELECT 'anual', YEAR(fecha), 'vph', COUNT(*) FROM vph GROUP BY YEAR(fecha)
UNION ALL
SELECT 'anual', YEAR(fecha_registro), 'auh', COUNT(*) FROM auh GROUP BY YEAR(fecha_registro);
");

echo "✅ Estadísticas actualizadas correctamente";
?>
