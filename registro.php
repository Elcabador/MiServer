<?php
include("conexion.php");

$usuario  = $_POST['nuevo_usuario'] ?? '';
$password = $_POST['nueva_contrasena'] ?? '';

if (empty($usuario) || empty($password)) {
    echo "<p style='color:red; text-align:center;'>❌ Rellena todos los campos</p>";
    echo "<a href='MiServer.html'>Volver</a>";
    exit();
}

// Comprobar si el usuario ya existe
$sql  = "SELECT * FROM usuarios WHERE Usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "<p style='color:red; text-align:center;'>❌ Ese usuario ya existe</p>";
    echo "<a href='MiServer.html'>Volver</a>";
} else {
    $sql  = "INSERT INTO usuarios (Usuario, Contraseña) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    echo "<p style='color:green; text-align:center;'>✅ Usuario registrado correctamente</p>";
    echo "<a href='MiServer.html'>Ir al login</a>";
}

$conn->close();
?>