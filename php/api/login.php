<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../conexion.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $_POST["email"] ?? $input["email"] ?? '';
    $contrasena = $_POST["contrasena"] ?? $input["contrasena"] ?? '';

    if (empty($email) || empty($contrasena)) {
        echo json_encode(["exito" => false, "mensaje" => "Faltan campos."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id_usuario, contrasena, nombre, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contrasena, $usuario["contrasena"])) {
            echo json_encode([
                "exito" => true,
                "mensaje" => "Inicio de sesión exitoso",
                "usuario" => [
                    "id" => $usuario["id_usuario"],
                    "nombre" => $usuario["nombre"],
                    "rol" => $usuario["rol"]
                ]
            ]);
        } else {
            echo json_encode(["exito" => false, "mensaje" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Correo no registrado."]);
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido."]);
}
?>
