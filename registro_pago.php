<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión para agregar un método de pago.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $tipo = $_POST["tipo"];
    $detalles = $_POST["detalles"]; // Se recomienda encriptar en un entorno real

    $sql = "INSERT INTO metodos_pago (usuario_id, tipo, detalles) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $usuario_id, $tipo, $detalles);

    if ($stmt->execute()) {
        echo "Método de pago agregado con éxito.";
    } else {
        echo "Error al agregar método de pago.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Método de Pago</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Agregar Método de Pago</h2>
    <form method="POST">
        <label>Tipo de Pago:</label>
        <select name="tipo">
            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
            <option value="paypal">PayPal</option>
            <option value="transferencia">Transferencia Bancaria</option>
        </select>
        <label>Detalles:</label>
        <input type="text" name="detalles" placeholder="Número de tarjeta o cuenta" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
