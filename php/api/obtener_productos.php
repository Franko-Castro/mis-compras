<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Manejo de preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Mostrar errores (solo desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../conexion.php");

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

$productos = [];

while($row = $result->fetch_assoc()){
    $productos[] = $row;
}

// Indicar que es JSON
header('Content-Type: application/json');

echo json_encode($productos);
?>