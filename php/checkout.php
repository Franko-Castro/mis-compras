<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../conexion.php';

// Leer cuerpo JSON
$input = file_get_contents("php://input");
file_put_contents("debug_input.txt", $input); // 👈 para depurar

$data = json_decode($input, true);

// Validación básica
if (empty($data) || !isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'Estructura JSON inválida']);
    exit;
}

// ⚙️ Ajuste del nombre del campo
$id_usuario = isset($data['usuario_id']) ? intval($data['usuario_id']) : null;
$total = isset($data['total']) ? floatval($data['total']) : 0;
$items = $data['items'];

// Insertar pedido
$stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (?, ?)");
$stmt->bind_param("id", $id_usuario, $total);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error al registrar pedido: ' . $stmt->error]);
    exit;
}

$id_pedido = $stmt->insert_id;

// Insertar detalle del pedido
$stmt = $conn->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
foreach ($items as $item) {
    $id_producto = intval($item['id']);
    $cantidad = intval($item['cantidad']);
    $precio = floatval($item['precio']);

    $stmt->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $precio, );
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar compra: ' . $stmt->error]);
        exit;
    }
}

// ✅ Todo correcto: limpiar carrito y redirigir
echo json_encode([
    'success' => true,
    'message' => 'Compra registrada correctamente.',
    'redirect' => 'gracias.html'
]);

$conn->close();
