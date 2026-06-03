<?php
header('Content-Type: application/json');
include '../conexion.php';

$sql = "SELECT p.id_producto, p.nombre, p.precio, p.imagen, u.nombre AS nombre_vendedor
        FROM productos p
        JOIN usuarios u ON p.id_vendedor = u.id_usuario";

$result = $conn->query($sql);

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode(["productos" => $productos]);