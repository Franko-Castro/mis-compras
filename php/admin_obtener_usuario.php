<?php
header('Content-Type: application/json');
include '../conexion.php';

// Auto-migración: Asegurar que existan las columnas necesarias
$check_cols = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'verificado'");
if ($check_cols->num_rows == 0) {
    $conn->query("ALTER TABLE usuarios ADD COLUMN verificado TINYINT(1) DEFAULT 0");
}

$check_rol = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'rol'");
if ($check_rol->num_rows == 0) {
    $conn->query("ALTER TABLE usuarios ADD COLUMN rol VARCHAR(20) DEFAULT 'comprador'");
}

// Consulta principal
$sql = "SELECT id_usuario, nombre, email, rol, verificado, foto_perfil, fecha_registro FROM usuarios";
$result = $conn->query($sql);

$usuarios = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

echo json_encode([
    "exito" => true,
    "usuarios" => $usuarios,
    "debug_count" => count($usuarios)
]);
?>