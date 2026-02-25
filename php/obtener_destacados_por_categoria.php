<?php
header("Content-Type: application/json");
include '../conexion.php';

// Número máximo de productos por categoría
$limite = 3;

// Obtener todas las categorías
$res = $conn->query("SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC");
$categorias = [];

if ($res) {
    while ($cat = $res->fetch_assoc()) {
        $id_cat = (int)$cat['id_categoria'];

        $stmt = $conn->prepare("SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.imagen, p.id_vendedor, u.nombre AS vendedor, p.fecha_publicacion
            FROM productos p
            LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
            WHERE p.id_categoria = ?
            ORDER BY p.fecha_publicacion DESC
            LIMIT ?");
        $stmt->bind_param("ii", $id_cat, $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }

        $categorias[] = [
            'id_categoria' => $id_cat,
            'categoria' => $cat['nombre'],
            'productos' => $productos
        ];

        $stmt->close();
    }
}

echo json_encode([
    'exito' => true,
    'categorias' => $categorias
]);

$conn->close();
?>