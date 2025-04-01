<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "12345";
$database = "desisDB";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error de conexión"]);
    exit;
}

$codigo = $_GET['codigo'] ?? $_POST['codigo'] ?? '';

if (!$codigo) {
    echo json_encode(["status" => "error", "message" => "Código no recibido"]);
    exit;
}

$stmt = $conexion->prepare("SELECT id FROM productos WHERE codigo = ?");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["existe" => true]);
} else {
    echo json_encode(["existe" => false]);
}

$stmt->close();
$conexion->close();
?>
