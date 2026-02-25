<?php

require __DIR__ . '/../conexion.php';

if(!isset($conexion) || $conexion->connect_error) {
    die("Error: No se pudo conectar a la base de datos. Verifica conexion.php");
}


?>