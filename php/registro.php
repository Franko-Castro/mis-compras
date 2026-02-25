<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Evitar errores por campos vacíos
    $nombre = $_POST["nombre"] ?? '';
    $email = $_POST["email"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';

    if (empty($nombre) || empty($email) || empty($contrasena)) {
        echo json_encode(["success" => false, "message" => "⚠️ Faltan campos."]);
        exit;
    }

    // Encriptar contraseña
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Evitar duplicados
    $verificar = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $verificar->bind_param("s", $email);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "⚠️ Este correo ya está registrado."]);
        exit;
    }

    // Insertar nuevo usuario
    $sql = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
    $sql->bind_param("sss", $nombre, $email, $hash);

    if ($sql->execute()) {
        echo json_encode(["success" => true, "message" => "🎉 ¡Registro exitoso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "❌ Error al registrar el usuario."]);
    }

    $sql->close();
    $conn->close();
}
?>
