<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carrito_id = $_POST["carrito_id"];

    // Eliminar producto del carrito
    $sql = "DELETE FROM carrito WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carrito_id);

    if ($stmt->execute()) {
        echo "<script>alert('Producto eliminado del carrito'); window.location='ver_carrito.php';</script>";
    } else {
        echo "Error al eliminar producto.";
    }

    $stmt->close();
}

$conn->close();
?>
