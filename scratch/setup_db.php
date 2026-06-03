<?php
include 'conexion.php';

$sql = "CREATE TABLE IF NOT EXISTS `imagenes_productos` (
  `id_imagen` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `ruta` varchar(255) NOT NULL,
  PRIMARY KEY (`id_imagen`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `imagenes_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'imagenes_productos' creada o ya existía.\n";
} else {
    echo "Error creando tabla: " . $conn->error . "\n";
}

$conn->close();
?>
