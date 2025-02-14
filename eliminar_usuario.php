<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es admin
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != "admin") {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Eliminar el usuario
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Usuario eliminado con éxito.'); window.location='ver_usuarios.php';</script>";
    } else {
        echo "Error al eliminar el usuario.";
    }

    $stmt->close();
} else {
    header("Location: ver_usuarios.php");
    exit();
}

$conn->close();
?>
