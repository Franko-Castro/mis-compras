<?php
header('Content-Type: application/json');
include '../conexion.php';

$id_usuario = $_POST['id_usuario'];
$total = $_POST['total'];

if (!isset($_FILES['comprobante'])) {
    echo json_encode(["exito" => false, "mensaje" => "No se recibió archivo"]);
    exit;
}

//  archivo
$archivo = $_FILES['comprobante']['name'];
$tmp = $_FILES['comprobante']['tmp_name'];

// generar nombre único
$nombreFinal = time() . "_" . $archivo;

// ruta física
$ruta = "../comprobantes/" . $nombreFinal;

// mover archivo
if (!move_uploaded_file($tmp, $ruta)) {
    echo json_encode(["exito" => false, "mensaje" => "Error al subir archivo"]);
    exit;
}

// guardar en BD (solo nombre del archivo)
$stmt = $conn->prepare("
  INSERT INTO ventas (id_usuario, total, estado, comprobante)
  VALUES (?, ?, 'pagado', ?)
");

$stmt->bind_param("ids", $id_usuario, $total, $nombreFinal);

if ($stmt->execute()) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false, "error" => $stmt->error]);
}