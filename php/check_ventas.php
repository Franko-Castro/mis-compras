<?php
include 'conexion.php';
$res = $conn->query("SHOW TABLES LIKE 'ventas'");
if ($res->num_rows > 0) {
    echo "Table 'ventas' exists.";
} else {
    echo "Table 'ventas' does NOT exist.";
}
?>
