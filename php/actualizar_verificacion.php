<?php
header('Content-Type: application/json');
include '../conexion.php';

if (!isset($_POST['id_usuario']) || !isset($_POST['verificado'])) {
    echo json_encode(["exito" => false, "error" => "Datos incompletos"]);
    exit;
}

$id = intval($_POST['id_usuario']);
$verificado = intval($_POST['verificado']);

$stmt = $conn->prepare("UPDATE usuarios SET verificado = ? WHERE id_usuario = ?");
$stmt->bind_param("ii", $verificado, $id);

if ($stmt->execute()) {
    echo json_encode(["exito" => true]);
} else {
    echo json_encode(["exito" => false, "error" => $stmt->error]);
}
?>
