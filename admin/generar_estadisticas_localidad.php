<?php
include("../db/conexion.php");


// 🔄 Limpiar solo las filas de localidad (para no borrar las demás estadísticas)
$conex->query("DELETE FROM estadisticas WHERE localidad IS NOT NULL");

// === SEMANAL ===
$conex->query("
INSERT INTO estadisticas (tipo, periodo, tipo_estudio, localidad, total)
SELECT 'semanal', DATE_FORMAT(a.fecha_registro, '%x-W%v'), 'auh', l.nombre, COUNT(*)
FROM auh a
JOIN localidades_la_matanza l ON a.localidad = l.id
GROUP BY DATE_FORMAT(a.fecha_registro, '%x-W%v'), l.nombre

UNION ALL
SELECT 'semanal', DATE_FORMAT(p.fecha, '%x-W%v'), 'presion', l.nombre, COUNT(*)
FROM presion p
JOIN localidades_la_matanza l ON p.localidad = l.id
GROUP BY DATE_FORMAT(p.fecha, '%x-W%v'), l.nombre

UNION ALL
SELECT 'semanal', DATE_FORMAT(g.fecha, '%x-W%v'), 'glucemia', l.nombre, COUNT(*)
FROM glucemia g
JOIN localidades_la_matanza l ON g.localidad = l.id
GROUP BY DATE_FORMAT(g.fecha, '%x-W%v'), l.nombre

UNION ALL
SELECT 'semanal', DATE_FORMAT(s.fecha, '%x-W%v'), 'sangre_oculta', l.nombre, COUNT(*)
FROM sangre_oculta s
JOIN localidades_la_matanza l ON s.localidad = l.id
GROUP BY DATE_FORMAT(s.fecha, '%x-W%v'), l.nombre

UNION ALL
SELECT 'semanal', DATE_FORMAT(si.fecha, '%x-W%v'), 'sifilis', l.nombre, COUNT(*)
FROM sifilis si
JOIN localidades_la_matanza l ON si.localidad = l.id
WHERE si.fecha <> '0000-00-00'
GROUP BY DATE_FORMAT(si.fecha, '%x-W%v'), l.nombre

UNION ALL
SELECT 'semanal', DATE_FORMAT(v.fecha, '%x-W%v'), 'vih', l.nombre, COUNT(*)
FROM vih v
JOIN localidades_la_matanza l ON v.localidad = l.id
GROUP BY DATE_FORMAT(v.fecha, '%x-W%v'), l.nombre

UNION ALL
SELECT 'semanal', DATE_FORMAT(vp.fecha, '%x-W%v'), 'vph', l.nombre, COUNT(*)
FROM vph vp
JOIN localidades_la_matanza l ON vp.localidad = l.id
GROUP BY DATE_FORMAT(vp.fecha, '%x-W%v'), l.nombre;
");

// === MENSUAL ===
$conex->query("
INSERT INTO estadisticas (tipo, periodo, tipo_estudio, localidad, total)
SELECT 'mensual', DATE_FORMAT(a.fecha_registro, '%Y-%m'), 'auh', l.nombre, COUNT(*)
FROM auh a
JOIN localidades_la_matanza l ON a.localidad = l.id
GROUP BY DATE_FORMAT(a.fecha_registro, '%Y-%m'), l.nombre

UNION ALL
SELECT 'mensual', DATE_FORMAT(p.fecha, '%Y-%m'), 'presion', l.nombre, COUNT(*)
FROM presion p
JOIN localidades_la_matanza l ON p.localidad = l.id
GROUP BY DATE_FORMAT(p.fecha, '%Y-%m'), l.nombre

UNION ALL
SELECT 'mensual', DATE_FORMAT(g.fecha, '%Y-%m'), 'glucemia', l.nombre, COUNT(*)
FROM glucemia g
JOIN localidades_la_matanza l ON g.localidad = l.id
GROUP BY DATE_FORMAT(g.fecha, '%Y-%m'), l.nombre

UNION ALL
SELECT 'mensual', DATE_FORMAT(s.fecha, '%Y-%m'), 'sangre_oculta', l.nombre, COUNT(*)
FROM sangre_oculta s
JOIN localidades_la_matanza l ON s.localidad = l.id
GROUP BY DATE_FORMAT(s.fecha, '%Y-%m'), l.nombre

UNION ALL
SELECT 'mensual', DATE_FORMAT(si.fecha, '%Y-%m'), 'sifilis', l.nombre, COUNT(*)
FROM sifilis si
JOIN localidades_la_matanza l ON si.localidad = l.id
WHERE si.fecha <> '0000-00-00'
GROUP BY DATE_FORMAT(si.fecha, '%Y-%m'), l.nombre

UNION ALL
SELECT 'mensual', DATE_FORMAT(v.fecha, '%Y-%m'), 'vih', l.nombre, COUNT(*)
FROM vih v
JOIN localidades_la_matanza l ON v.localidad = l.id
GROUP BY DATE_FORMAT(v.fecha, '%Y-%m'), l.nombre

UNION ALL
SELECT 'mensual', DATE_FORMAT(vp.fecha, '%Y-%m'), 'vph', l.nombre, COUNT(*)
FROM vph vp
JOIN localidades_la_matanza l ON vp.localidad = l.id
GROUP BY DATE_FORMAT(vp.fecha, '%Y-%m'), l.nombre;
");

// === ANUAL ===
$conex->query("
INSERT INTO estadisticas (tipo, periodo, tipo_estudio, localidad, total)
SELECT 'anual', YEAR(a.fecha_registro), 'auh', l.nombre, COUNT(*)
FROM auh a
JOIN localidades_la_matanza l ON a.localidad = l.id
GROUP BY YEAR(a.fecha_registro), l.nombre

UNION ALL
SELECT 'anual', YEAR(p.fecha), 'presion', l.nombre, COUNT(*)
FROM presion p
JOIN localidades_la_matanza l ON p.localidad = l.id
GROUP BY YEAR(p.fecha), l.nombre

UNION ALL
SELECT 'anual', YEAR(g.fecha), 'glucemia', l.nombre, COUNT(*)
FROM glucemia g
JOIN localidades_la_matanza l ON g.localidad = l.id
GROUP BY YEAR(g.fecha), l.nombre

UNION ALL
SELECT 'anual', YEAR(s.fecha), 'sangre_oculta', l.nombre, COUNT(*)
FROM sangre_oculta s
JOIN localidades_la_matanza l ON s.localidad = l.id
GROUP BY YEAR(s.fecha), l.nombre

UNION ALL
SELECT 'anual', YEAR(si.fecha), 'sifilis', l.nombre, COUNT(*)
FROM sifilis si
JOIN localidades_la_matanza l ON si.localidad = l.id
WHERE si.fecha <> '0000-00-00'
GROUP BY YEAR(si.fecha), l.nombre

UNION ALL
SELECT 'anual', YEAR(v.fecha), 'vih', l.nombre, COUNT(*)
FROM vih v
JOIN localidades_la_matanza l ON v.localidad = l.id
GROUP BY YEAR(v.fecha), l.nombre

UNION ALL
SELECT 'anual', YEAR(vp.fecha), 'vph', l.nombre, COUNT(*)
FROM vph vp
JOIN localidades_la_matanza l ON vp.localidad = l.id
GROUP BY YEAR(vp.fecha), l.nombre;
");

echo "✅ Estadísticas por localidad actualizadas correctamente.";
?>
