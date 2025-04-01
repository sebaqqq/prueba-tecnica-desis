<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "12345";
$database = "desisDB";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error de conexión a la base de datos"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$codigo = trim($data['codigo'] ?? '');
$nombre = trim($data['nombre'] ?? '');
$bodega = intval($data['bodega'] ?? 0);
$sucursal = intval($data['sucursal'] ?? 0);
$moneda = intval($data['moneda'] ?? 0);
$precio = floatval($data['precio'] ?? 0);
$descripcion = trim($data['descripcion'] ?? '');
$materialesArray = $data['materiales'] ?? [];

if (!$codigo || !$nombre || !$bodega || !$sucursal || !$moneda || !$precio || !$descripcion || count($materialesArray) < 2) {
    echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios o no válidos."]);
    exit;
}

$check = $conexion->prepare("SELECT id FROM productos WHERE codigo = ?");
$check->bind_param("s", $codigo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El código del producto ya existe."]);
    exit;
}
$check->close();

$materiales = implode(",", $materialesArray);

$stmt = $conexion->prepare("
    INSERT INTO productos 
    (codigo, nombre, bodega_id, sucursal_id, moneda_id, precio, descripcion, materiales)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssiiidss", $codigo, $nombre, $bodega, $sucursal, $moneda, $precio, $descripcion, $materiales);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Producto guardado correctamente"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se pudo guardar el producto"]);
}

$stmt->close();
$conexion->close();
?>
