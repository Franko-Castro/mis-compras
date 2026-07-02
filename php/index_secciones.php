<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include '../conexion.php';

$response = [
    "exito" => true,
    "mas_vendidos" => [],
    "ultimas_vistas" => [],
    "alimentos" => []
];

// 1. Lo más vendido (Top 5 productos con más ventas)
$sqlMasVendidos = "
    SELECT p.id_producto, p.nombre, p.precio, p.imagen, COALESCE(SUM(dv.cantidad), 0) as total_vendido
    FROM productos p
    LEFT JOIN detalle_venta dv ON p.id_producto = dv.id_producto
    GROUP BY p.id_producto
    ORDER BY total_vendido DESC
    LIMIT 5
";
$resMasVendidos = $conn->query($sqlMasVendidos);
if ($resMasVendidos) {
    while ($row = $resMasVendidos->fetch_assoc()) {
        $row['imagen'] = !empty($row['imagen']) ? 'imagenes/' . $row['imagen'] : 'imagenes/default-product.svg';
        $response["mas_vendidos"][] = $row;
    }
}

// 2. Últimas vistas relacionadas (Recibe IDs por GET o devuelve recientes/aleatorios si está vacío)
$vistos = isset($_GET['vistos']) ? $_GET['vistos'] : '';
if (!empty($vistos)) {
    // Escapar y limpiar IDs
    $ids = array_map('intval', explode(',', $vistos));
    $ids = array_filter($ids);
    if (!empty($ids)) {
        $in_clause = implode(',', $ids);
        // Obtener esos productos y también otros de la misma categoría
        $sqlVistas = "
            SELECT id_producto, nombre, precio, imagen 
            FROM productos 
            WHERE id_producto IN ($in_clause) 
               OR id_categoria IN (SELECT id_categoria FROM productos WHERE id_producto IN ($in_clause))
            ORDER BY FIELD(id_producto, $in_clause) DESC, fecha_publicacion DESC
            LIMIT 5
        ";
        $resVistas = $conn->query($sqlVistas);
        if ($resVistas) {
            while ($row = $resVistas->fetch_assoc()) {
                $row['imagen'] = !empty($row['imagen']) ? 'imagenes/' . $row['imagen'] : 'imagenes/default-product.svg';
                $response["ultimas_vistas"][] = $row;
            }
        }
    }
}

// Si no hay historial de vistas o no devolvió resultados, enviar los más recientes
if (empty($response["ultimas_vistas"])) {
    $sqlRecientes = "
        SELECT id_producto, nombre, precio, imagen 
        FROM productos 
        ORDER BY fecha_publicacion DESC 
        LIMIT 5
    ";
    $resRecientes = $conn->query($sqlRecientes);
    if ($resRecientes) {
        while ($row = $resRecientes->fetch_assoc()) {
            $row['imagen'] = !empty($row['imagen']) ? 'imagenes/' . $row['imagen'] : 'imagenes/default-product.svg';
            $response["ultimas_vistas"][] = $row;
        }
    }
}

// 3. Alimentos (Categorías 5: Frutas y verduras, 6: Carnes y mariscos)
$sqlAlimentos = "
    SELECT id_producto, nombre, precio, imagen 
    FROM productos 
    WHERE id_categoria IN (5, 6)
    ORDER BY fecha_publicacion DESC
    LIMIT 5
";
$resAlimentos = $conn->query($sqlAlimentos);
if ($resAlimentos) {
    while ($row = $resAlimentos->fetch_assoc()) {
        $row['imagen'] = !empty($row['imagen']) ? 'imagenes/' . $row['imagen'] : 'imagenes/default-product.svg';
        $response["alimentos"][] = $row;
    }
}

$conn->close();

echo json_encode($response);
