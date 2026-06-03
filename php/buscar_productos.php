<?php
header("Content-Type: application/json");
include "../conexion.php";

$texto = $_GET["q"] ?? '';

if ($texto == '') {
    echo json_encode(["exito" => false, "productos" => []]);
    exit;
}

$buscar = "%" . $texto . "%";

$sql = $conn->prepare("
    SELECT p.id_producto, p.nombre, p.precio, p.imagen, u.verificado, u.whatsapp
    FROM productos p
    LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
    WHERE p.nombre LIKE ? 
       OR p.descripcion LIKE ?
       OR p.precio LIKE ?
");
$sql->bind_param("sss", $buscar, $buscar, $buscar);
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
