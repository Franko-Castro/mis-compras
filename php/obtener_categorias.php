<?php
header("Content-Type: application/json");
include "../conexion.php";  // RUTA CORRECTA

$sql = "SELECT * FROM categorias ORDER BY nombre ASC";
$res = $conn->query($sql);

$categorias = [];

while ($row = $res->fetch_assoc()) {
    $categorias[] = $row;
}

echo json_encode([
    "exito" => true,
    "categorias" => $categorias
]);
?>
