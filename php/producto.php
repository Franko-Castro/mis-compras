<?php
header("Content-Type: application/json");
include '../conexion.php';

// Desactivar reporte de errores en producción para no romper el JSON
error_reporting(0);
ini_set('display_errors', 0);

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID no proporcionado");
    }

    $id = intval($_GET['id']);

    // 1. Obtener datos principales del producto
    $sql = "SELECT 
                p.id_producto,
                p.nombre,
                p.descripcion,
                p.precio,
                p.imagen,
                p.fecha_publicacion,
                u.id_usuario AS id_vendedor,
                u.nombre AS vendedor,
                u.foto_perfil,
                u.verificado,
                u.whatsapp
            FROM productos p
            LEFT JOIN usuarios u ON p.id_vendedor = u.id_usuario
            WHERE p.id_producto = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Error en la preparación SQL: " . $conn->error);
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo json_encode(["exito" => false, "mensaje" => "Producto no encontrado"]);
        exit;
    }

    $producto = $res->fetch_assoc();
    
    // Fallbacks para datos nulos
    $producto["vendedor"] = $producto["vendedor"] ?? "Vendedor desconocido";
    $producto["id_vendedor"] = $producto["id_vendedor"] ?? 0;
    $producto["imagen_url"] = "imagenes/" . ($producto["imagen"] ?: "default-product.svg");
    $producto["imagenes_extra"] = [];

    // 2. Obtener imágenes adicionales (Con manejo de error por si la tabla no existe)
    try {
        $sqlExtra = "SELECT ruta FROM imagenes_productos WHERE id_producto = ?";
        $stmtExtra = $conn->prepare($sqlExtra);
        if ($stmtExtra) {
            $stmtExtra->bind_param("i", $id);
            $stmtExtra->execute();
            $resExtra = $stmtExtra->get_result();
            if ($resExtra) {
                while ($row = $resExtra->fetch_assoc()) {
                    $producto["imagenes_extra"][] = "imagenes/" . $row["ruta"];
                }
            }
        }
    } catch (Throwable $e) {
        // Ignorar errores de imágenes extra (ej: tabla faltante)
    }

    echo json_encode([
        "exito" => true,
        "producto" => $producto
    ]);

} catch (Throwable $err) {
    echo json_encode([
        "exito" => false, 
        "mensaje" => $err->getMessage()
    ]);
}
?>
