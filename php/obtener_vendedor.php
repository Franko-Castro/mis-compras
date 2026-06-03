<?php
// php/obtener_vendedor.php
require_once 'conexion.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de vendedor no especificado']);
    exit;
}

$id_vendedor = intval($_GET['id']);

try {
    // Obtener información del vendedor
    $stmt = $conn->prepare("
        SELECT 
            u.id_usuario,
            u.nombre,
            u.email,
            u.fecha_registro,
            uv.descripcion,
            uv.imagen,
            uv.telefono,
            uv.ubicacion,
            uv.politica_envios,
            uv.garantia,
            uv.metodos_pago,
            uv.atencion_cliente,
            uv.verificado,
            COALESCE(AVG(v.rating), 0) as rating,
            COUNT(v.id_valoracion) as valoraciones
        FROM usuarios u
        LEFT JOIN usuario_vendedor uv ON u.id_usuario = uv.id_usuario
        LEFT JOIN valoraciones v ON u.id_usuario = v.id_vendedor
        WHERE u.id_usuario = ?
        GROUP BY u.id_usuario
    ");
    
    $stmt->bind_param("i", $id_vendedor);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($vendedor = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'vendedor' => $vendedor
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Vendedor no encontrado'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la consulta: ' . $e->getMessage()
    ]);
}

$conn->close();
?>