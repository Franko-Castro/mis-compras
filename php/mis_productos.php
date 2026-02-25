<?php 
header("Content-Type: application/json");
include '../conexion.php';

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo json_encode(["exito" => false, "mensaje" => "ID no proporcionado"]);
    exit;
}

$id_vendedor = intval($_GET["id"]);

$stmt = $conn->prepare("
    SELECT 
        id_producto,
        nombre,
        descripcion,
        precio,
        imagen,
        DATE_FORMAT(fecha_publicacion, '%d/%m/%Y') AS fecha_publicacion
    FROM productos
    WHERE id_vendedor = ?
    ORDER BY fecha_publicacion DESC
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
