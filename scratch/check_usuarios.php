<?php
include 'conexion.php';
$res = $conn->query("DESCRIBE usuarios");
if ($res) {
    while($row = $res->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}
?>
