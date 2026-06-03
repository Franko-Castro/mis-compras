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

echo json_encode([
    "exito" => true,
    "producto" => $res->fetch_assoc()
]);

$stmt->close();
$conn->close();
