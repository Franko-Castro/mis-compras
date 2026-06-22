<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include("../conexion.php");

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');

if(
    empty($nombre) ||
    empty($email) ||
    empty($contrasena)
){
    echo json_encode([
        "exito" => false,
        "mensaje" => "Todos los campos son obligatorios"
    ]);
    exit;
}

$consulta = $conexion->prepare(
    "SELECT id_usuario FROM usuarios WHERE email = ?"
);

$consulta->bind_param("s", $email);
$consulta->execute();
$resultado = $consulta->get_result();

if($resultado->num_rows > 0){
    echo json_encode([
        "exito" => false,
        "mensaje" => "El correo ya está registrado"
    ]);
    exit;
}

$insertar = $conexion->prepare(
    "INSERT INTO usuarios
    (
        nombre,
        email,
        contrasena
    )
    VALUES
    (
        ?,
        ?,
        ?
    )"
);

$insertar->bind_param(
    "sss",
    $nombre,
    $email,
    $contrasena
);

if($insertar->execute()){
    echo json_encode([
        "exito" => true,
        "mensaje" => "Usuario registrado correctamente"
    ]);
}else{
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al registrar usuario"
    ]);
}