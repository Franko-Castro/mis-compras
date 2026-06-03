<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
include '../conexion.php';

$id_vendedor = $_GET['id_vendedor'];

$sql = "
SELECT 
  v.id_venta,
  v.total,
  v.estado,
  v.fecha,
  p.nombre AS producto,
  dv.cantidad,
  COALESCE(u.nombre, 'Cliente') AS comprador_nombre,
  COALESCE(u.whatsapp, '') AS comprador_whatsapp,
  COALESCE(u.ubicacion, 'No especificada') AS comprador_direccion
FROM detalle_venta dv
JOIN ventas v ON dv.id_venta = v.id_venta
JOIN productos p ON dv.id_producto = p.id_producto
LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
WHERE p.id_vendedor = ?
ORDER BY v.id_venta DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();

$result = $stmt->get_result();

$ventas = [];

while ($row = $result->fetch_assoc()) {
    $ventas[] = $row;
}

echo json_encode(["ventas" => $ventas]);