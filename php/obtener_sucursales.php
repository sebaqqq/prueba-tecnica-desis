<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "12345";
$database = "desisDB";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$id_bodega = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT id, nombre FROM sucursales WHERE id_bodega = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_bodega);
$stmt->execute();

$resultado = $stmt->get_result();

$sucursales = [];

while ($fila = $resultado->fetch_assoc()) {
    $sucursales[] = $fila;
}

echo json_encode($sucursales);
$conexion->close();
?>
