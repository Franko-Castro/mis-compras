<?php
header("Content-Type: application/json");
require '../conexion.php';

// Validar que llegan los datos requeridos
if (
    !isset($_POST['id_producto']) || empty($_POST['id_producto']) ||
    !isset($_POST['nombre'])      || empty($_POST['nombre']) ||
    !isset($_POST['precio'])      || !is_numeric($_POST['precio']) ||
    !isset($_POST['descripcion']) || empty($_POST['descripcion'])
) {
    echo json_encode(["exito" => false, "mensaje" => "Faltan campos requeridos."]);
    exit;
}

$id_producto = intval($_POST['id_producto']);
$nombre      = trim($_POST['nombre']);
$precio      = floatval($_POST['precio']);
$descripcion = trim($_POST['descripcion']);

// Obtener imagen actual del producto
$sqlImg = "SELECT imagen FROM productos WHERE id_producto = ?";
$stmtImg = $conn->prepare($sqlImg);
if (!$stmtImg) {
    echo json_encode(["exito" => false, "mensaje" => "Error preparando consulta."]);
    exit;
}
$stmtImg->bind_param("i", $id_producto);
$stmtImg->execute();
$resImg = $stmtImg->get_result();
$row = $resImg->fetch_assoc();
$stmtImg->close();

if (!$row) {
    echo json_encode(["exito" => false, "mensaje" => "Producto no encontrado."]);
    exit;
}

$nombreImagen = $row['imagen'];

// Si se subió una nueva imagen, procesarla
if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($ext, $permitidos)) {
        echo json_encode(["exito" => false, "mensaje" => "Formato de imagen no permitido."]);
        exit;
    }

    $nombreImagen = uniqid() . "." . $ext;
    $destino = "../imagenes/" . $nombreImagen;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
        echo json_encode(["exito" => false, "mensaje" => "Error al subir la imagen."]);
        exit;
    }
}

// Actualizar el producto en la base de datos
$sql = "UPDATE productos SET nombre=?, precio=?, descripcion=?, imagen=? WHERE id_producto=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["exito" => false, "mensaje" => "Error preparando actualización."]);
    exit;
}

$stmt->bind_param("sdssi", $nombre, $precio, $descripcion, $nombreImagen, $id_producto);

if ($stmt->execute()) {
    echo json_encode(["exito" => true, "mensaje" => "Producto actualizado correctamente."]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al actualizar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
