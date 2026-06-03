<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include '../conexion.php';

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo json_encode(["exito" => false, "mensaje" => "ID no proporcionado"]);
    exit;
}

$id_vendedor = intval($_GET["id"]);

$stmt = $conn->prepare("
    SELECT 
        p.id_producto,
        p.nombre,
        p.descripcion,
        p.precio,
        p.imagen,
        DATE_FORMAT(p.fecha_publicacion, '%d/%m/%Y') AS fecha_publicacion,
        u.verificado,
        u.whatsapp
    FROM productos p
    LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
    WHERE p.id_vendedor = ?
    ORDER BY p.fecha_publicacion DESC
");

$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$resultado = $stmt->get_result();

$productos = [];

while ($fila = $resultado->fetch_assoc()) {
    $fila['imagen'] = !empty($fila['imagen'])
        ? 'imagenes/' . $fila['imagen']
        : 'imagenes/default-product.svg';

    $productos[] = $fila;
}

echo json_encode([
    "exito" => true,
    "productos" => $productos
]);

$stmt->close();
$conn->close();
