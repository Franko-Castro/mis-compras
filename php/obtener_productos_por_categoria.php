<?php
header("Content-Type: application/json");
include "../conexion.php";

$sql = "SELECT 
    c.id_categoria,
    c.nombre AS categoria,
    p.id_producto,
    p.nombre AS producto_nombre,
    p.descripcion,
    p.precio,
    p.imagen,
    u.id_usuario AS id_vendedor,
    u.nombre AS vendedor
FROM productos p
LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
ORDER BY COALESCE(c.nombre, 'Sin categoría') ASC, p.fecha_publicacion DESC";

$res = $conn->query($sql);

$grupos = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $cat = $row['categoria'] ? $row['categoria'] : 'Sin categoría';
        if (!isset($grupos[$cat])) {
            $grupos[$cat] = [
                'categoria' => $cat,
                'productos' => []
            ];
        }

        $grupos[$cat]['productos'][] = [
            'id_producto' => $row['id_producto'],
            'nombre' => $row['producto_nombre'],
            'descripcion' => $row['descripcion'],
            'precio' => $row['precio'],
            'imagen' => $row['imagen'],
            'id_vendedor' => $row['id_vendedor'],
            'vendedor' => $row['vendedor'] ? $row['vendedor'] : 'Anónimo'
        ];
    }
}

// Convertir a array indexado
$resultado = array_values($grupos);

echo json_encode([
    'exito' => true,
    'categorias' => $resultado
]);
?>