<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "tienda_online";
$port = 522; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
