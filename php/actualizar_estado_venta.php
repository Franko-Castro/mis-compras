<?php
include '../conexion.php';

$id = $_POST['id_venta'];
$estado = $_POST['estado'];

$stmt = $conn->prepare("UPDATE ventas SET estado = ? WHERE id_venta = ?");
$stmt->bind_param("si", $estado, $id);

echo json_encode(["exito" => $stmt->execute()]);