<?php
header("Content-Type: application/json");
include '../conexion.php';

$id_producto = $_GET["id"] ?? null;
$id_producto = is_numeric($id_producto) ? intval($id_producto) : null;

if (!$id_producto) {
    echo json_encode(["exito" => false, "mensaje" => "ID inválido"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT r.calificacion, r.comentario, r.fecha, u.nombre " .
    "FROM resenas r " .
    "JOIN usuarios u ON r.id_usuario = u.id_usuario " .
    "WHERE r.id_producto = ? " .
    "ORDER BY r.fecha DESC"
);
$stmt->bind_param("i", $id_producto);
$stmt->execute();

$result = $stmt->get_result();
$resenas = [];

while ($row = $result->fetch_assoc()) {
    $resenas[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    "exito" => true,
    "resenas" => $resenas
]);
