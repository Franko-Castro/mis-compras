<?php
session_start();
include '../conexion.php';
header("Content-Type: application/json");

// Desactivar errores visibles para no romper el JSON, pero registrarlos
error_reporting(E_ALL);
ini_set('display_errors', 0);

$id_vendedor = $_SESSION['id_usuario'] ?? $_POST['id_vendedor'] ?? null;

if (!$id_vendedor) {
  echo json_encode(["success" => false, "message" => "No hay sesión activa. Inicia sesión para publicar."]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $precio = $_POST['precio'] ?? '';
  $categoria = $_POST['categoria'] ?? null;

  if (empty($nombre) || empty($descripcion) || $precio === '') {
    echo json_encode(["success" => false, "message" => "⚠️ Todos los campos son obligatorios."]);
    exit;
  }

  $id_categoria = is_numeric($categoria) && intval($categoria) > 0 ? intval($categoria) : null;

  // 1. Manejar imagen principal
  if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "⚠️ Debes subir al menos la imagen principal."]);
    exit;
  }

  $primaryExt = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
  $primaryImageName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $primaryExt;
  
  if (!move_uploaded_file($_FILES['imagen']['tmp_name'], "../imagenes/" . $primaryImageName)) {
    echo json_encode(["success" => false, "message" => "⚠️ Error al subir la imagen principal a la carpeta."]);
    exit;
  }

  // 2. Insertar producto (Transacción)
  $conn->begin_transaction();
  try {
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, id_vendedor, id_categoria, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Error preparando SQL de producto: " . $conn->error);
    
    $stmt->bind_param("ssdsii", $nombre, $descripcion, $precio, $primaryImageName, $id_vendedor, $id_categoria);
    if (!$stmt->execute()) throw new Exception("Error ejecutando SQL: " . $stmt->error);
    
    $id_producto = $conn->insert_id;

    // 3. Manejar imágenes extra
    if (isset($_FILES['imagenes_extra'])) {
      $extraFiles = $_FILES['imagenes_extra'];
      
      // Si se subieron archivos (imagenes_extra es un array)
      if (is_array($extraFiles['name'])) {
        $fileCount = count($extraFiles['name']);

        for ($i = 0; $i < $fileCount; $i++) {
          if ($extraFiles['error'][$i] === UPLOAD_ERR_OK) {
            $extraExt = pathinfo($extraFiles['name'][$i], PATHINFO_EXTENSION);
            $extraUniqueName = time() . '_' . bin2hex(random_bytes(4)) . '_extra_' . $i . '.' . $extraExt;
            
            if (move_uploaded_file($extraFiles['tmp_name'][$i], "../imagenes/" . $extraUniqueName)) {
              $sqlImg = "INSERT INTO imagenes_productos (id_producto, ruta) VALUES (?, ?)";
              $stmtImg = $conn->prepare($sqlImg);
              
              if ($stmtImg) {
                $stmtImg->bind_param("is", $id_producto, $extraUniqueName);
                $stmtImg->execute();
              } else {
                // Si la tabla no existe, registramos el error pero no cancelamos la publicación completa
                error_log("No se pudo guardar imagen extra (probablemente tabla faltante): " . $conn->error);
              }
            }
          }
        }
      }
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "✅ Producto publicado correctamente."]);

  } catch (Exception $e) {
    $conn->rollback();
    error_log("Fallo al publicar: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "❌ Error: " . $e->getMessage()]);
  }
}
?>
