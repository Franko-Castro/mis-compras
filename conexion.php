<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
// Configuración de la conexión a la base de datos con laragon
/*$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "tienda_online";
$port = 522;*/

// Crear conexión
//$conn = new mysqli($servername, $username, $password, $database, $port);

// Configuración de la conexión a la base de datos con docker
$conn = new mysqli(
    "mysql",
    "root",
    "root",
    "tienda_online"
);
// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
