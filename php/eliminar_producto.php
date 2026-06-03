<?php
header("Content-Type: application/json");

// Ruta correcta al archivo de conexión (estamos dentro de php/)
include '../conexion.php';

// Aceptar id por POST (form), GET o JSON en el body
$id = null;
if (isset($_POST['id_producto'])) {
  $id = $_POST['id_producto'];
} elseif (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $raw = file_get_contents('php://input');
  $json = json_decode($raw, true);
  if (is_array($json) && isset($json['id_producto'])) $id = $json['id_producto'];
}

if (!$id || !is_numeric($id)) {
  echo json_encode(["exito" => false, "mensaje" => "ID no proporcionado o inválido."]);
  exit;
}

$id = intval($id);

// Ejecutar eliminación
$stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo json_encode(["exito" => true, "mensaje" => "Producto eliminado correctamente."]);
} else {
  echo json_encode(["exito" => false, "mensaje" => "Error al eliminar el producto."]);
}

$stmt->close();
$conn->close();
?>
