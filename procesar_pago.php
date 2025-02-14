<?php

session_start();
include 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["metodo_pago"], $_POST["pedido_id"])) {
    $metodo_pago = $_POST["metodo_pago"];
    $pedido_id = $_POST["pedido_id"];

    // Actualizar el estado del pedido a "cancelado"
    $sql_update = "UPDATE pedidos SET estado = 'cancelado' WHERE id = ? AND usuario_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $pedido_id, $usuario_id);

    if ($stmt_update->execute()) {
        // Obtener la dirección del usuario
        $sql_direccion = "SELECT direccion FROM usuarios WHERE id = ?";
        $stmt_direccion = $conn->prepare($sql_direccion);
        $stmt_direccion->bind_param("i", $usuario_id);
        $stmt_direccion->execute();
        $result_direccion = $stmt_direccion->get_result();
        
        if ($row = $result_direccion->fetch_assoc()) {
            $direccion = $row["direccion"];
            echo "<script>
                    alert('Pago realizado con éxito. Su producto llegará en 48 horas a la dirección: $direccion');
                    window.location.href = 'ver_pedidos.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Pago realizado, pero no se encontró la dirección.');
                    window.location.href = 'ver_pedidos.php';
                  </script>";
        }

        $stmt_direccion->close();
    } else {
        echo "<script>
                alert('Error al procesar el pago.');
                window.history.back();
              </script>";
    }

    $stmt_update->close();
}

$conn->close();
?>


/*session_start();
include 'conexion.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST["metodo_pago"])) {
    echo "Debes seleccionar un método de pago.";
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$metodo_pago_id = $_POST["metodo_pago"];

$conn->begin_transaction(); // Iniciar una transacción

try {
    // Crear un nuevo pedido
    $sql_pedido = "INSERT INTO pedidos (usuario_id, metodo_pago_id, fecha, estado) VALUES (?, ?, NOW(), 'Pendiente')";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("ii", $usuario_id, $metodo_pago_id);
    $stmt_pedido->execute();
    $pedido_id = $stmt_pedido->insert_id; // Obtener el ID del pedido generado

    // Obtener productos del carrito
    $sql_carrito = "SELECT c.producto_id, p.nombre, p.precio, c.cantidad
                    FROM carrito c
                    JOIN productos p ON c.producto_id = p.id
                    WHERE c.usuario_id = ?";
    $stmt_carrito = $conn->prepare($sql_carrito);
    $stmt_carrito->bind_param("i", $usuario_id);
    $stmt_carrito->execute();
    $result_carrito = $stmt_carrito->get_result();

    while ($row = $result_carrito->fetch_assoc()) {
        $producto_id = $row["producto_id"];
        $precio = $row["precio"];
        $cantidad = $row["cantidad"];

        // Insertar en detalle_pedido
        $sql_detalle = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt_detalle = $conn->prepare($sql_detalle);
        $stmt_detalle->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
        $stmt_detalle->execute();

        // Reducir stock del producto
        $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $cantidad, $producto_id);
        $stmt_stock->execute();
    }

    // Vaciar el carrito
    $sql_vaciar = "DELETE FROM carrito WHERE usuario_id = ?";
    $stmt_vaciar = $conn->prepare($sql_vaciar);
    $stmt_vaciar->bind_param("i", $usuario_id);
    $stmt_vaciar->execute();

    $conn->commit(); // Confirmar la transacción

    echo "<script>alert('Pago realizado con éxito.'); window.location.href='ver_pedidos.php';</script>";
} catch (Exception $e) {
    $conn->rollback(); // Revertir cambios en caso de error
    echo "Error al procesar el pago: " . $e->getMessage();
}

$conn->close();
?>/*

