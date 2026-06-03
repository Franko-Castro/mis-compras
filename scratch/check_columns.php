<?php
include 'conexion.php';
$res = $conn->query("DESCRIBE ventas");
while($row = $res->fetch_assoc()){
    print_r($row);
}
$res = $conn->query("DESCRIBE detalle_venta");
while($row = $res->fetch_assoc()){
    print_r($row);
}
?>
