<?php
header("Content-Type: application/json");
include '../conexion.php';

if (!isset($_GET['id'])) {
    echo json_encode(["exito" => false, "mensaje" => "ID no proporcionado"]);
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT 
            p.id_producto,
            p.nombre,
            p.descripcion,
            p.precio,
            p.imagen,
            p.fecha_publicacion,
            u.id_usuario AS id_vendedor,
            u.nombre AS vendedor
        FROM productos p
        JOIN usuarios u ON p.id_vendedor = u.id_usuario
        WHERE p.id_producto = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["exito" => false]);
    exit;
}

$producto = $res->fetch_assoc();
$producto["imagen_url"] = "imagenes/" . $producto["imagen"];

echo json_encode([
    "exito" => true,
    "producto" => $producto
]);
