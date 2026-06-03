<?php
// Script para actualizar la información general del perfil del usuario (Nombre y Ubicación)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Incluir la conexión a la base de datos
include '../conexion.php';

// Verificar que los datos necesarios lleguen por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener los datos del cuerpo de la petición (JSON)
    $data = json_decode(file_get_contents("php://input"), true);
    
    $id_usuario = $data['id_usuario'] ?? null;
    $nombre = $data['nombre'] ?? null;
    $ubicacion = $data['ubicacion'] ?? null;
    $whatsapp = $data['whatsapp'] ?? null;

    // Validar que el ID y el nombre no estén vacíos
    if (!$id_usuario || !$nombre) {
        echo json_encode(["exito" => false, "mensaje" => "Faltan datos obligatorios (ID o Nombre)"]);
        exit;
    }

    try {
        // Preparar la consulta SQL para actualizar nombre, ubicación y whatsapp
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, ubicacion = ?, whatsapp = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $nombre, $ubicacion, $whatsapp, $id_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(["exito" => true, "mensaje" => "Perfil actualizado correctamente"]);
        } else {
            echo json_encode(["exito" => false, "mensaje" => "Error al actualizar en la base de datos"]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["exito" => false, "mensaje" => "Error en el servidor: " . $e->getMessage()]);
    }

} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
}

$conn->close();
?>
