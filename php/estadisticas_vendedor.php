<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include '../conexion.php';

if (!isset($_GET["id_vendedor"]) || !is_numeric($_GET["id_vendedor"])) {
    echo json_encode(["exito" => false, "mensaje" => "ID no válido"]);
    exit;
}

$id_vendedor = intval($_GET["id_vendedor"]);

// ── 1. VENTAS REALIZADAS ──────────────────────────────────────────────────────
// Contamos cuántas filas en detalle_venta corresponden a productos de este vendedor
$stmtVentas = $conn->prepare("
    SELECT COUNT(dv.id_detalle) AS total_ventas
    FROM detalle_venta dv
    JOIN productos p ON dv.id_producto = p.id_producto
    WHERE p.id_vendedor = ?
");
$stmtVentas->bind_param("i", $id_vendedor);
$stmtVentas->execute();
$rowVentas = $stmtVentas->get_result()->fetch_assoc();
$total_ventas = intval($rowVentas["total_ventas"] ?? 0);
$stmtVentas->close();

// ── 2. CALIFICACIÓN PROMEDIO ──────────────────────────────────────────────────
// Promedio de todas las reseñas de todos los productos de este vendedor
$stmtCal = $conn->prepare("
    SELECT 
        ROUND(AVG(r.calificacion), 1) AS promedio,
        COUNT(r.id_resena)            AS total_resenas
    FROM resenas r
    JOIN productos p ON r.id_producto = p.id_producto
    WHERE p.id_vendedor = ?
");
$stmtCal->bind_param("i", $id_vendedor);
$stmtCal->execute();
$rowCal = $stmtCal->get_result()->fetch_assoc();
$promedio_cal   = $rowCal["promedio"]      !== null ? floatval($rowCal["promedio"])      : 0;
$total_resenas  = $rowCal["total_resenas"] !== null ? intval($rowCal["total_resenas"])   : 0;
$stmtCal->close();

// ── 3. VISTAS TOTALES ─────────────────────────────────────────────────────────
// Usamos el número de veces que los productos del vendedor aparecen en detalle_venta
// (cuántas veces fueron "vistos" por compradores que llegaron a comprarlos),
// más el total de reseñas como proxy de interacciones. Si la BD no tiene una
// tabla de vistas, devolvemos el conteo de ventas × 10 como estimación visible.
// (Se puede reemplazar fácilmente si se agrega una tabla real de vistas.)
$stmtProds = $conn->prepare("
    SELECT COUNT(*) AS total_productos
    FROM productos
    WHERE id_vendedor = ?
");
$stmtProds->bind_param("i", $id_vendedor);
$stmtProds->execute();
$rowProds = $stmtProds->get_result()->fetch_assoc();
$total_productos = intval($rowProds["total_productos"] ?? 0);
$stmtProds->close();

// Sin columna real de vistas en la BD, estimamos: ventas * 10 + reseñas * 3
// Esto da un número coherente. Cuando se agregue la tabla de vistas, se cambia aquí.
$vistas_estimadas = ($total_ventas * 10) + ($total_resenas * 3);

$conn->close();

echo json_encode([
    "exito"           => true,
    "ventas"          => $total_ventas,
    "calificacion"    => $promedio_cal,
    "total_resenas"   => $total_resenas,
    "vistas"          => $vistas_estimadas,
    "total_productos" => $total_productos
]);
