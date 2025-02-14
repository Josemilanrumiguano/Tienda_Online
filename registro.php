<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $tipo = "cliente"; // Por defecto, los nuevos usuarios serán clientes

    $sql = "INSERT INTO usuarios (nombre, email, contrasena, direccion, telefono, tipo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $email, $contrasena, $direccion, $telefono, $tipo);

    if ($stmt->execute()) {
        echo "Registro exitoso. <a href='login.php'>Inicia sesión aquí</a>";
    } else {
        echo "Error al registrar usuario.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
        /* Estilos generales */
body {
    font-family: 'Poppins', sans-serif;
    background: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

/* Contenedor del formulario */
.container {
    max-width: 400px;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Título */
h2 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin-bottom: 20px;
}

/* Campos del formulario */
form {
    display: flex;
    flex-direction: column;
    padding: 10px;
}

input {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    transition: 0.3s;
}

input:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}

/* Botón de registro */
button {
    background: #1abc9c;
    color: #fff;
    padding: 12px;
    font-size: 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 10px;
}

button:hover {
    background: #16a085;
}

/* Enlace de inicio de sesión */
a {
    display: block;
    margin-top: 15px;
    color: #3498db;
    text-decoration: none;
    font-size: 0.9rem;
}

a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 480px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    h2 {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>
<div class="container">
    <h2>Registro de Usuario</h2>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="text" name="direccion" placeholder="Dirección">
        <input type="text" name="telefono" placeholder="Teléfono">
        <button type="submit">Registrarse</button>
    </form>
    <a href="login.php">¿Ya tienes una cuenta? Inicia sesión</a>
</div>

</body>
</html>
