<?php
header('Content-Type: application/json');
include '../conexion.php';

$id_venta = $_GET['id_venta'] ?? '';

if (empty($id_venta)) {
    echo json_encode(["exito" => false, "mensaje" => "ID de venta no proporcionado"]);
    exit;
}

// 1. Obtener info general de la venta y el comprador
$sql_venta = "
    SELECT v.*, u.nombre as comprador_nombre, u.email as comprador_email, u.ubicacion as comprador_ubicacion, u.foto_perfil as comprador_foto, u.whatsapp as comprador_whatsapp
    FROM ventas v
    JOIN usuarios u ON v.id_usuario = u.id_usuario
    WHERE v.id_venta = ?
";

$stmt = $conn->prepare($sql_venta);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$res_venta = $stmt->get_result()->fetch_assoc();

if (!$res_venta) {
    echo json_encode(["exito" => false, "mensaje" => "Venta no encontrada"]);
    exit;
}

// 2. Obtener productos de la venta y sus vendedores
$sql_detalle = "
    SELECT dv.*, p.nombre as producto_nombre, p.imagen as producto_imagen, ven.nombre as vendedor_nombre, ven.foto_perfil as vendedor_foto
    FROM detalle_venta dv
    JOIN productos p ON dv.id_producto = p.id_producto
    JOIN usuarios ven ON p.id_vendedor = ven.id_usuario
    WHERE dv.id_venta = ?
";

$stmt_det = $conn->prepare($sql_detalle);
$stmt_det->bind_param("i", $id_venta);
$stmt_det->execute();
$res_detalle = $stmt_det->get_result();

$productos = [];
while ($row = $res_detalle->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode([
    "exito" => true,
    "venta" => $res_venta,
    "productos" => $productos
]);
?>
