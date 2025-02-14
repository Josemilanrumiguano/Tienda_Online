<?php
session_start();
include 'conexion.php';

// Verifica si el usuario es administrador
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] !== "admin") {
    echo "Acceso denegado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $tipo = "admin"; // Forzar a que el tipo sea "admin"

    $sql = "INSERT INTO usuarios (nombre, email, contrasena, direccion, telefono, tipo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $email, $contrasena, $direccion, $telefono, $tipo);

    if ($stmt->execute()) {
        echo "Administrador registrado con éxito.";
    } else {
        echo "Error al registrar administrador.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>
</head>
<body>
    <h2>Registrar Administrador</h2>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="text" name="direccion" placeholder="Dirección">
        <input type="text" name="telefono" placeholder="Teléfono">
        <button type="submit">Registrar Administrador</button>
    </form>
</body>
</html>
