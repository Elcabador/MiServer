<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['activo' => false, 'debug' => 'no session']);
    exit;
}

include('conexion.php');

$usuario = $_SESSION['usuario'];
$sid     = session_id();

$stmt = $conn->prepare("SELECT sesion_id FROM usuarios WHERE Usuario = ?");
$stmt->bind_param('s', $usuario);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'activo' => $fila && $fila['sesion_id'] === $sid,
    'debug_usuario' => $usuario,
    'debug_sid_php' => $sid,
    'debug_sid_bd'  => $fila['sesion_id'] ?? 'NULL'
]);

$conn->close();
?>