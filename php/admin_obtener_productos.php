<?php
header('Content-Type: application/json');
include '../conexion.php';

$sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.imagen, p.fecha_publicacion, 
               u.nombre AS nombre_vendedor, c.nombre AS categoria,
               (SELECT GROUP_CONCAT(ruta) FROM imagenes_productos WHERE id_producto = p.id_producto) AS imagenes_adicionales
        FROM productos p
        LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";

$result = $conn->query($sql);

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode(["productos" => $productos]);