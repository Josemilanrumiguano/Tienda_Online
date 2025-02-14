<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión para realizar un pedido.";
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

// Obtener productos del carrito
$sql = "SELECT producto_id, cantidad FROM carrito WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $total = 0;
    $productos = [];

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
        $producto_id = $row["producto_id"];
        $cantidad = $row["cantidad"];

        // Obtener precio del producto
        $precioQuery = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
        $precioQuery->bind_param("i", $producto_id);
        $precioQuery->execute();
        $precioResult = $precioQuery->get_result();
        $precio = $precioResult->fetch_assoc()["precio"];
        $total += $precio * $cantidad;
    }

    // Insertar pedido
    $pedidoStmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
    $pedidoStmt->bind_param("id", $usuario_id, $total);
    $pedidoStmt->execute();
    $pedido_id = $pedidoStmt->insert_id;

    // Insertar detalles del pedido
    foreach ($productos as $producto) {
        $detalleStmt = $conn->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
        $detalleStmt->bind_param("iiid", $pedido_id, $producto["producto_id"], $producto["cantidad"], $precio);
        $detalleStmt->execute();
    }

    // Vaciar carrito
    $conn->query("DELETE FROM carrito WHERE usuario_id = $usuario_id");

    echo "Pedido realizado con éxito.";
} else {
    echo "No hay productos en el carrito.";
}

$stmt->close();
$conn->close();
?>
