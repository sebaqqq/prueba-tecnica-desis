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

$sql = "SELECT id, nombre FROM bodegas";
$resultado = $conexion->query($sql);

$bodegas = [];

while ($fila = $resultado->fetch_assoc()) {
    $bodegas[] = $fila;
}

echo json_encode($bodegas);
$conexion->close();
?>
