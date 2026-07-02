<?php
header("Content-Type: application/json");
require '../conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["exito" => false, "mensaje" => "ID no válido."]);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT p.id_producto, p.nombre, p.precio, p.descripcion, p.imagen, u.verificado, u.whatsapp, u.nombre as vendedor_nombre
        FROM productos p
        LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
        WHERE p.id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["exito" => false, "mensaje" => "Producto no encontrado."]);
    exit;
}

$producto = $res->fetch_assoc();

// Fetch extra images
$sql_extra = "SELECT ruta FROM imagenes_productos WHERE id_producto = ?";
$stmt_extra = $conn->prepare($sql_extra);
$stmt_extra->bind_param("i", $id);
$stmt_extra->execute();
$res_extra = $stmt_extra->get_result();

$imagenes_extra = [];
while ($row = $res_extra->fetch_assoc()) {
    $imagenes_extra[] = $row['ruta'];
}
$producto['imagenes_extra'] = $imagenes_extra;
$stmt_extra->close();

echo json_encode([
    "exito" => true,
    "producto" => $producto
]);

$stmt->close();
$conn->close();
