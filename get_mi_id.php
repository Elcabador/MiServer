<?php
session_start();
header('Content-Type: application/json');
echo json_encode(['id' => $_SESSION['usuario_id'] ?? 0]);
?>