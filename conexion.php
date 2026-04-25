<?php
// Datos de tu base de datos — cámbialos por los tuyos
$host     = "localhost";
$usuario  = "root";        // tu usuario de MySQL
$password = "";            // tu contraseña de MySQL
$base     = "miserver";   // el nombre de tu base de datos

$conn = new mysqli($host, $usuario, $password, $base);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
