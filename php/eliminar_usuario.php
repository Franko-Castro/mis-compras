<?php
include '../conexion.php';

if (!isset($_POST['id_usuario'])) {
    echo json_encode(["exito" => false, "error" => "ID no proporcionado"]);
    exit;
}

$id = $_POST['id_usuario'];

// Verificar rol primero
$check = $conn->prepare("SELECT rol FROM usuarios WHERE id_usuario = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["exito" => false, "error" => "Usuario no encontrado"]);
    exit;
}

$res = $result->fetch_assoc();

if ($res['rol'] === 'admin') {
    echo json_encode(["exito" => false, "error" => "No puedes eliminar a un administrador"]);
    exit;
}

$sql = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false, "error" => "Error al eliminar. Posibles dependencias activas."]);
}