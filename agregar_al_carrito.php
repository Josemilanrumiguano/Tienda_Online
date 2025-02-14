<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión para agregar productos al carrito.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $producto_id = $_POST["producto_id"];
    $cantidad = $_POST["cantidad"];

    // Verificar si el producto ya está en el carrito
    $check = $conn->prepare("SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?");
    $check->bind_param("ii", $usuario_id, $producto_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nuevaCantidad = $row["cantidad"] + $cantidad;
        $update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id = ?");
        $update->bind_param("ii", $nuevaCantidad, $row["id"]);
        $update->execute();
        echo "Cantidad actualizada en el carrito.";
    } else {
        $stmt = $conn->prepare("INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
        if ($stmt->execute()) {
            echo "Producto agregado al carrito.";
        } else {
            echo "Error al agregar producto.";
        }
        $stmt->close();
    }
    $check->close();
    $conn->close();
}
?>
