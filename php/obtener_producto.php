<?php
require '../conexion.php';

$id = $_GET['id'];

$sql = "SELECT id_producto, nombre, precio, descripcion, imagen 
        FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["exito" => false]);
    exit;
}

echo json_encode([
    "exito" => true,
    "producto" => $res->fetch_assoc()
]);
