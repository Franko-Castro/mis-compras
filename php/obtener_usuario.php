<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include '../conexion.php';

// Validar que el ID de usuario está presente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["exito" => false, "mensaje" => "ID de usuario no proporcionado."]);
    exit;
}

$id_usuario = $_GET['id'];

try {
    // Preparar la consulta para obtener los datos del usuario
    $stmt = $conn->prepare("SELECT id_usuario, nombre, email, DATE_FORMAT(fecha_registro, '%d/%m/%Y') as fecha_registro FROM usuarios WHERE id_usuario = ?");
    
    if ($stmt === false) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // El usuario fue encontrado, devolver sus datos
        $usuario = $resultado->fetch_assoc();
        echo json_encode(["exito" => true, "usuario" => $usuario]);
    } else {
        // El usuario no fue encontrado
        echo json_encode(["exito" => false, "mensaje" => "Usuario no encontrado."]);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Capturar cualquier error de la base de datos
    http_response_code(500);
    echo json_encode(["exito" => false, "mensaje" => "Error en el servidor: " . $e->getMessage()]);
}
?>