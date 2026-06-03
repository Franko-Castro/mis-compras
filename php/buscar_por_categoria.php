<?php
header("Content-Type: application/json");
include "../conexion.php";

$id = $_GET["id"] ?? '';

if ($id === '') {
    echo json_encode(["exito" => false, "productos" => []]);
    exit;
}

$sql = $conn->prepare("
    SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.imagen, u.verificado, u.whatsapp
    FROM productos p
    LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
    WHERE p.id_categoria = ?
");
$sql->bind_param("s", $id);
$sql->execute();
$res = $sql->get_result();

$productos = [];
while ($row = $res->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode([
    "exito" => true,
    "productos" => $productos
]);
?>
