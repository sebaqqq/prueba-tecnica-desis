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

$sql = "SELECT id, nombre FROM monedas";
$resultado = $conexion->query($sql);

$monedas = [];

while ($fila = $resultado->fetch_assoc()) {
    $monedas[] = $fila;
}

echo json_encode($monedas);
$conexion->close();
?>
