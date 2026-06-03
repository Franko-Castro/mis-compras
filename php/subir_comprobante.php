<?php
header('Content-Type: application/json');
include '../conexion.php';

$id_venta = $_POST['id_venta'];

if (!isset($_FILES['comprobante'])) {
    echo json_encode(["exito" => false]);
    exit;
}

$archivo = $_FILES['comprobante']['name'];
$tmp = $_FILES['comprobante']['tmp_name'];

$nombreFinal = time() . "_" . $archivo;
$ruta = "../comprobantes/" . $nombreFinal;

if (!move_uploaded_file($tmp, $ruta)) {
    echo json_encode(["exito" => false]);
    exit;
}

// actualizar venta
$stmt = $conn->prepare("
  UPDATE ventas 
  SET comprobante = ?, estado = 'pagado'
  WHERE id_venta = ?
");

$stmt->bind_param("si", $nombreFinal, $id_venta);

echo json_encode(["exito" => $stmt->execute()]);