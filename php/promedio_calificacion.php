<?php
header("Content-Type: application/json");
include '../conexion.php';

$id_producto = $_GET["id"] ?? null;
$id_producto = is_numeric($id_producto) ? intval($id_producto) : null;

if (!$id_producto) {
    echo json_encode(["promedio" => 0, "total" => 0]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT AVG(calificacion) AS promedio, COUNT(*) AS total FROM resenas WHERE id_producto = ?"
);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

$prom = $res["promedio"] !== null ? round($res["promedio"], 1) : 0;
$total = $res["total"] ?? 0;

$stmt->close();
$conn->close();

echo json_encode([
    "promedio" => $prom,
    "total" => $total
]);
