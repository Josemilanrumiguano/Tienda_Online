<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $producto_id = $_POST["producto_id"];
    $cantidad = $_POST["cantidad"];

    // Verificar si el producto ya está en el carrito
    $sql = "SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $carrito = $result->fetch_assoc();

    if ($carrito) {
        // Si ya existe en el carrito, actualizar cantidad
        $nueva_cantidad = $carrito["cantidad"] + $cantidad;
        $sql = "UPDATE carrito SET cantidad = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nueva_cantidad, $carrito["id"]);
    } else {
        // Si no está en el carrito, agregarlo
        $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Producto agregado al carrito'); window.location='productos.php';</script>";
    } else {
        echo "Error al agregar al carrito.";
    }

    $stmt->close();
}

$conn->close();
?>
