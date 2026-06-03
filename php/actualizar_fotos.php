<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    exit;
}

$id_usuario = $_POST['id_usuario'] ?? null;
$tipo = $_POST['tipo'] ?? null; // 'perfil' o 'portada'
$foto = $_FILES['foto'] ?? null;

if (!$id_usuario || !$tipo || !$foto) {
    echo json_encode(["exito" => false, "mensaje" => "Datos incompletos"]);
    exit;
}

// Configuración según el tipo — carpetas dedicadas
$directorio = "";
$columna = "";

if ($tipo === 'perfil') {
    $directorio = "../fotos_perfil/";
    $columna = "foto_perfil";
} else if ($tipo === 'portada') {
    $directorio = "../fotos_portada/";
    $columna = "foto_portada";
} else {
    echo json_encode(["exito" => false, "mensaje" => "Tipo de foto no válido"]);
    exit;
}

// Crear directorio si no existe
if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

// Validar archivo
$extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
$nombre_archivo = time() . "_" . $id_usuario . "." . $extension;
$ruta_destino = $directorio . $nombre_archivo;

// Tipos permitidos
$permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array(strtolower($extension), $permitidos)) {
    echo json_encode(["exito" => false, "mensaje" => "Tipo de archivo no permitido"]);
    exit;
}

if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
    try {
        // Actualizar base de datos con solo el nombre del archivo
        $stmt = $conn->prepare("UPDATE usuarios SET $columna = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $nombre_archivo, $id_usuario);
        
        if ($stmt->execute()) {
            echo json_encode([
                "exito" => true, 
                "mensaje" => "Foto actualizada correctamente", 
                "archivo" => $nombre_archivo
            ]);
        } else {
            echo json_encode(["exito" => false, "mensaje" => "Error al actualizar la base de datos"]);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["exito" => false, "mensaje" => "Error en el servidor: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al mover el archivo al servidor"]);
}

$conn->close();
?>
