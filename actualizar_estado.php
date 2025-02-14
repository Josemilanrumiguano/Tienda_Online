<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] != "admin") {
    echo "Acceso denegado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pedido_id = $_POST["pedido_id"];
    $nuevo_estado = $_POST["estado"];

    $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);

    if ($stmt->execute()) {
        echo "Estado actualizado correctamente.";
    } else {
        echo "Error al actualizar estado.";
    }

    $stmt->close();
    $conn->close();
}
?>
