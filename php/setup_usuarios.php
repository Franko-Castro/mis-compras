<?php
include 'conexion.php';

// Añadir columna verificado si no existe
$sql_verificado = "ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS verificado TINYINT(1) DEFAULT 0";
if ($conn->query($sql_verificado)) {
    echo "Columna 'verificado' procesada correctamente.\n";
} else {
    echo "Error al añadir 'verificado': " . $conn->error . "\n";
}

// Añadir columna rol si no existe
$sql_rol = "ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS rol VARCHAR(20) DEFAULT 'comprador'";
if ($conn->query($sql_rol)) {
    echo "Columna 'rol' procesada correctamente.\n";
} else {
    echo "Error al añadir 'rol': " . $conn->error . "\n";
}

// Asegurar que el admin esté verificado
$sql_admin = "UPDATE usuarios SET verificado = 1, rol = 'admin' WHERE email LIKE '%admin%' OR nombre LIKE '%admin%'";
$conn->query($sql_admin);

echo "Base de datos actualizada.";
?>
