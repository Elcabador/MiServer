<?php
session_start();
include("conexion.php");

$usuario  = $_POST['txtusuarios'] ?? '';
$password = $_POST['txtcontrasena'] ?? '';

$sql  = "SELECT * FROM usuarios WHERE Usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    if ($password == $fila['Contraseña']) {
        $_SESSION['usuario'] = $usuario;

        // Guardar sesion_id en la base de datos
        $sid = session_id();
        $id  = $fila['ID'];
        $stmt2 = $conn->prepare("UPDATE usuarios SET sesion_id = ? WHERE ID = ?");
        $stmt2->bind_param("si", $sid, $id);
        $stmt2->execute();

        if ($fila['Admin'] == 1) {
            $_SESSION['usuario_id'] = $fila['ID']; // ← añade esta línea
            header("Location: index.html");
            exit();
        } else {
            header("Location: Login.html");
            exit();
        }
    } else {
        header("Location: error.html");
        exit();
    }
} else {
    header("Location: error.html");
    exit();
}

$conn->close();
?>