<?php
header("Access-Control-Allow-Origin: *");

$archivo = $_GET['archivo'] ?? '';
$tipo = $_GET['tipo'] ?? 'portada'; // 'portada' o 'perfil'

if (empty($archivo)) {
    http_response_code(404);
    exit;
}

// Carpetas a buscar según el tipo, en orden de prioridad
if ($tipo === 'perfil') {
    $carpetas = [
        '../fotos_perfil/',
        '../fotos de usuarios/',
        '../imagenes/',
        '../imagendes de productos/',
    ];
} else {
    $carpetas = [
        '../fotos_portada/',
        '../imagenes/',
        '../fotos de usuarios/',
        '../imagendes de productos/',
    ];
}

$ruta_encontrada = null;

foreach ($carpetas as $carpeta) {
    $ruta = $carpeta . $archivo;
    if (file_exists($ruta)) {
        $ruta_encontrada = $ruta;
        break;
    }
}

if (!$ruta_encontrada) {
    http_response_code(404);
    exit;
}

// Determinar tipo MIME
$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
$mime_types = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
];
$mime = $mime_types[$extension] ?? 'image/jpeg';

header("Content-Type: $mime");
header("Cache-Control: public, max-age=86400"); // Cache de 1 día
readfile($ruta_encontrada);
exit;
?>
