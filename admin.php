<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$db = getDB();

switch ($action) {

    case 'listar':
        $res = $db->query("SELECT ID, Usuario, Admin,
                           CASE WHEN sesion_id IS NOT NULL THEN 1 ELSE 0 END AS activo
                           FROM usuarios ORDER BY ID");
        $usuarios = [];
        while ($row = $res->fetch_assoc()) {
            $usuarios[] = $row;
        }
        echo json_encode(['ok' => true, 'usuarios' => $usuarios]);
        break;

    case 'expulsar':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['error' => 'ID inválido.']); break;
        }
        $stmt = $db->prepare("SELECT sesion_id, Usuario FROM usuarios WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            echo json_encode(['error' => 'Usuario no encontrado.']); break;
        }
        if (!$row['sesion_id']) {
            echo json_encode(['ok' => true, 'msg' => $row['Usuario'] . ' no tiene sesión activa.']);
            break;
        }

        $mi_id = intval($_POST['mi_id'] ?? 0);
        if ($mi_id > 0 && $mi_id === $id) {
            echo json_encode(['error' => 'No puedes expulsarte a ti mismo.']);
            break;
        }

        $stmt2 = $db->prepare("UPDATE usuarios SET sesion_id = NULL WHERE ID = ?");
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        echo json_encode(['ok' => true, 'msg' => 'Sesión de ' . htmlspecialchars($row['Usuario']) . ' cerrada correctamente.']);
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['error' => 'ID inválido.']); break;
        }
        $stmt = $db->prepare("DELETE FROM usuarios WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        echo json_encode(['ok' => true, 'msg' => 'Usuario eliminado correctamente.']);
        break;

    case 'añadir':
        $usuario  = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($usuario) || empty($password)) {
            echo json_encode(['error' => 'Rellena todos los campos.']); break;
        }
        $check = $db->prepare("SELECT ID FROM usuarios WHERE Usuario = ?");
        $check->bind_param('s', $usuario);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(['error' => 'Ese usuario ya existe.']); break;
        }
        $stmt = $db->prepare("INSERT INTO usuarios (Usuario, Contraseña) VALUES (?, ?)");
        $stmt->bind_param('ss', $usuario, $password);
        $stmt->execute();
        echo json_encode(['ok' => true, 'msg' => 'Usuario añadido correctamente.']);
        break;

    case 'toggle_admin':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['error' => 'ID inválido.']); break;
        }
        $stmt = $db->prepare("UPDATE usuarios SET Admin = NOT Admin WHERE ID = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        echo json_encode(['ok' => true, 'msg' => 'Rol actualizado correctamente.']);
        break;

    default:
        echo json_encode(['error' => 'Acción desconocida.']);
}

$db->close();
?>