<?php
include '../conexion.php';

$id = $_POST['id_producto'];

$stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false]);
}