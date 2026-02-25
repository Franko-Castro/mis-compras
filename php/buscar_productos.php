<?php
header("Content-Type: application/json");
include "conexion.php";

$texto = $_GET["q"] ?? '';

if ($texto == '') {
    echo json_encode(["exito" => false, "productos" => []]);
    exit;
}

$buscar = "%" . $texto . "%";

$sql = $conn->prepare("
    SELECT id_producto, nombre, precio, imagen
    FROM productos
    WHERE nombre LIKE ? 
       OR descripcion LIKE ?
       OR precio LIKE ?
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
