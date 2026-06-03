<?php
header('Content-Type: application/json');
include '../conexion.php';

$sql = "SELECT v.*, u.nombre as comprador_nombre, u.foto_perfil as comprador_foto, u.whatsapp, u.ubicacion,
        (SELECT GROUP_CONCAT(DISTINCT ven.nombre SEPARATOR ', ') 
         FROM detalle_venta dv 
         JOIN productos p ON dv.id_producto = p.id_producto 
         JOIN usuarios ven ON p.id_vendedor = ven.id_usuario 
         WHERE dv.id_venta = v.id_venta) as vendedor_nombre,
        (SELECT GROUP_CONCAT(p.nombre SEPARATOR ', ') 
         FROM detalle_venta dv 
         JOIN productos p ON dv.id_producto = p.id_producto 
         WHERE dv.id_venta = v.id_venta) as productos_nombres,
        (SELECT ven.whatsapp 
         FROM detalle_venta dv 
         JOIN productos p ON dv.id_producto = p.id_producto 
         JOIN usuarios ven ON p.id_vendedor = ven.id_usuario 
         WHERE dv.id_venta = v.id_venta 
         LIMIT 1) as vendedor_whatsapp,
        (SELECT ven.foto_perfil 
         FROM detalle_venta dv 
         JOIN productos p ON dv.id_producto = p.id_producto 
         JOIN usuarios ven ON p.id_vendedor = ven.id_usuario 
         WHERE dv.id_venta = v.id_venta 
         LIMIT 1) as vendedor_foto
        FROM ventas v
        JOIN usuarios u ON v.id_usuario = u.id_usuario
        ORDER BY v.id_venta DESC";

$result = $conn->query($sql);

$ventas = [];

while ($row = $result->fetch_assoc()) {
    $ventas[] = $row;
}

echo json_encode(["ventas" => $ventas]);