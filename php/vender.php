<?php
session_start();
include '../conexion.php';
header("Content-Type: application/json");

$id_vendedor = $_SESSION['id_usuario'] ?? $_POST['id_vendedor'] ?? null;

if (!$id_vendedor) {
  echo json_encode(["success" => false, "message" => "No hay sesión activa. Inicia sesión para publicar.", "exito" => false, "mensaje" => "No hay sesión activa. Inicia sesión para publicar."]);
  exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $precio = $_POST['precio'] ?? '';
  $categoria = $_POST['categoria'] ?? null;

  if (empty($nombre) || empty($descripcion) || $precio === '') {
    echo json_encode(["success" => false, "message" => "⚠️ Todos los campos son obligatorios.", "exito" => false, "mensaje" => "Todos los campos son obligatorios."]);
    exit;
  }

  if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "⚠️ Debes subir una imagen válida.", "exito" => false, "mensaje" => "Debes subir una imagen válida."]);
    exit;
  }

  // Preparar nombre único para la imagen
  $originalName = $_FILES['imagen']['name'];
  $ext = pathinfo($originalName, PATHINFO_EXTENSION);
  $uniqueName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
  $rutaDestino = "../imagenes/" . $uniqueName;

  if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
    // id_categoria puede ser nulo
    $id_categoria = is_numeric($categoria) && intval($categoria) > 0 ? intval($categoria) : null;

    if ($id_categoria !== null) {
      $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, id_vendedor, id_categoria, fecha_publicacion)
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssdsii", $nombre, $descripcion, $precio, $uniqueName, $id_vendedor, $id_categoria);
    } else {
      $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, id_vendedor, fecha_publicacion)
              VALUES (?, ?, ?, ?, ?, NOW())";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $uniqueName, $id_vendedor);
    }

    if ($stmt->execute()) {
      echo json_encode(["success" => true, "message" => "✅ Producto publicado correctamente.", "exito" => true, "mensaje" => "Producto publicado correctamente."]);
    } else {
      error_log("Error al ejecutar stmt: " . $stmt->error);
      echo json_encode(["success" => false, "message" => "❌ Error al guardar el producto.", "exito" => false, "mensaje" => "Error al guardar el producto."]);
    }
  } else {
    echo json_encode(["success" => false, "message" => "⚠️ No se pudo subir la imagen.", "exito" => false, "mensaje" => "No se pudo subir la imagen."]);
  }
}
?>
