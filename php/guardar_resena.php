<?php
header("Content-Type: application/json");
// Archivo dentro de php/ -> subir conexion desde raíz
include '../conexion.php';

// Soportar POST tradicional o JSON en body
$raw = file_get_contents('php://input');
$json = json_decode($raw, true);

$id_producto = $_POST["id_producto"] ?? ($json['id_producto'] ?? null);
$id_usuario  = $_POST["id_usuario"]  ?? ($json['id_usuario'] ?? null);
$calificacion = $_POST["calificacion"] ?? ($json['calificacion'] ?? null);
$comentario = $_POST["comentario"] ?? ($json['comentario'] ?? "");

// Validaciones básicas
$id_producto = is_numeric($id_producto) ? intval($id_producto) : null;
$id_usuario = is_numeric($id_usuario) ? intval($id_usuario) : null;
$calificacion = is_numeric($calificacion) ? intval($calificacion) : null;

if (!$id_producto || !$id_usuario || !$calificacion) {
    echo json_encode(["exito" => false, "mensaje" => "Datos incompletos"]);
    exit;
}

// Verificar si ya calificó
$check = $conn->prepare("SELECT id_resena FROM resenas WHERE id_producto=? AND id_usuario=?");
$check->bind_param("ii", $id_producto, $id_usuario);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo json_encode(["exito" => false, "mensaje" => "Ya calificaste este producto"]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

$stmt = $conn->prepare(
    "INSERT INTO resenas (id_producto, id_usuario, calificacion, comentario)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("iiis", $id_producto, $id_usuario, $calificacion, $comentario);

if ($stmt->execute()) {
    echo json_encode(["exito" => true, "mensaje" => "Reseña guardada"]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al guardar"]);
}

$stmt->close();
$conn->close();
