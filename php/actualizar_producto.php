<?php
require '../conexion.php';

$id_producto = $_POST['id_producto'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$descripcion = $_POST['descripcion'];

// Obtener imagen actual
$sqlImg = "SELECT imagen FROM productos WHERE id_producto = ?";
$stmtImg = $conn->prepare($sqlImg);
$stmtImg->bind_param("i", $id_producto);
$stmtImg->execute();
$resImg = $stmtImg->get_result();
$imgActual = $resImg->fetch_assoc()['imagen'];
$stmtImg->close();

$nombreImagen = $imgActual;

// Si se sube una nueva imagen
if (!empty($_FILES['imagen']['name'])) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombreImagen = uniqid() . "." . $ext;
    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../imagenes/" . $nombreImagen
    );
}

// Actualizar producto
$sql = "UPDATE productos 
        SET nombre=?, precio=?, descripcion=?, imagen=? 
        WHERE id_producto=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdssi", $nombre, $precio, $descripcion, $nombreImagen, $id_producto);

if ($stmt->execute()) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al actualizar"]);
}
