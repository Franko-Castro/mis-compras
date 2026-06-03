<?php
header('Content-Type: application/json');
include '../conexion.php';

//  leer JSON correctamente
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// validar
if (!$data || !isset($data['usuario_id']) || !isset($data['total'])) {
    echo json_encode([
        "success" => false,
        "message" => "Estructura JSON inválida"
    ]);
    exit;
}

$id_usuario = $data['usuario_id'];
$total = $data['total'];

// guardar venta
$conn->begin_transaction();

try {
    // 1. Insertar en la tabla 'ventas'
    $stmt = $conn->prepare("INSERT INTO ventas (id_usuario, total, estado) VALUES (?, ?, 'pendiente')");
    $stmt->bind_param("id", $id_usuario, $total);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al insertar en ventas: " . $stmt->error);
    }
    
    $id_venta = $conn->insert_id;

    // 2. Insertar los items en 'detalle_ventas' (si existe la tabla)
    // Nota: Si no tienes la tabla 'detalle_ventas', puedes crearla con:
    // CREATE TABLE detalle_ventas (id_detalle INT AUTO_INCREMENT PRIMARY KEY, id_venta INT, id_producto INT, cantidad INT, precio DECIMAL(10,2))
    if (isset($data['items']) && is_array($data['items'])) {
        $stmt_item = $conn->prepare("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
        
        foreach ($data['items'] as $item) {
            $id_p = $item['id'];
            $cant = $item['cantidad'];
            $pre = $item['precio'];
            $stmt_item->bind_param("iiid", $id_venta, $id_p, $cant, $pre);
            $stmt_item->execute();
        }
    }

    $conn->commit();
    echo json_encode([
        "success" => true,
        "order_id" => $id_venta
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        "success" => false,
        "message" => "Error al procesar la compra: " . $e->getMessage()
    ]);
}
$items = $data['items'];

foreach ($items as $item) {
    $id_producto = $item['id'];
    $cantidad = $item['cantidad'];

    $stmt2 = $conn->prepare("
        INSERT INTO detalle_venta (id_venta, id_producto, cantidad)
        VALUES (?, ?, ?)
    ");

    $stmt2->bind_param("iii", $id_venta, $id_producto, $cantidad);
    $stmt2->execute();
}