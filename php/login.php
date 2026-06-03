<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';

    if (empty($email) || empty($contrasena)) {
        echo json_encode(["éxito" => false, "mensaje" => "Faltan campos."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id_usuario, contrasena, nombre, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contrasena, $usuario["contrasena"])) {

            session_start();
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];

            echo json_encode([
                "éxito" => true,
                "mensaje" => "Inicio de sesión exitoso",
                "usuario" => [
                    "id" => $usuario["id_usuario"],
                    "nombre" => $usuario["nombre"],
                    "rol" => $usuario["rol"]
                ]
            ]);

        } else {
            echo json_encode(["éxito" => false, "mensaje" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["éxito" => false, "mensaje" => "Correo no registrado."]);
    }
}
?>