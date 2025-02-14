<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carrito_id = $_POST["carrito_id"];
    $cantidad = $_POST["cantidad"];

    // Actualizar cantidad en el carrito
    $sql = "UPDATE carrito SET cantidad = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cantidad, $carrito_id);

    if ($stmt->execute()) {
        echo "<script>alert('Carrito actualizado'); window.location='ver_carrito.php';</script>";
    } else {
        echo "Error al actualizar el carrito.";
    }

    $stmt->close();
}

$conn->close();
?>
