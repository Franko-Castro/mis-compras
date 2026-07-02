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

// ── VALIDACIÓN: el vendedor no puede comprar sus propios productos ──
if (isset($data['items']) && is_array($data['items'])) {
    $ids_productos = array_map(fn($i) => intval($i['id']), $data['items']);

    if (!empty($ids_productos)) {
        $placeholders = implode(',', array_fill(0, count($ids_productos), '?'));
        $types = str_repeat('i', count($ids_productos));

        $stmtCheck = $conn->prepare("
            SELECT COUNT(*) as total
            FROM productos
            WHERE id_producto IN ($placeholders)
              AND id_vendedor = ?
        ");

        // bind_param dinámico: productos + id_usuario al final
        $bindValues = array_merge($ids_productos, [$id_usuario]);
        $stmtCheck->bind_param($types . 'i', ...$bindValues);
        $stmtCheck->execute();
        $rowCheck = $stmtCheck->get_result()->fetch_assoc();
        $stmtCheck->close();

        if (intval($rowCheck['total']) > 0) {
            echo json_encode([
                "success" => false,
                "message" => "No puedes comprar tus propios productos"
            ]);
            exit;
        }
    }
}

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