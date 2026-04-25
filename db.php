<?php
// ── Configuración de conexión ──────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Cambia por tu usuario MySQL
define('DB_PASS', '');           // Cambia por tu contraseña MySQL
define('DB_NAME', 'miserver');   // Nombre de tu base de datos

function getDB(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
