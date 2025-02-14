<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es admin
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != "admin") {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Obtener los datos del usuario a editar
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $tipo = $_POST['tipo'];

        // Actualizar los datos del usuario
        $sql_update = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, tipo = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssi", $nombre, $email, $telefono, $tipo, $usuario_id);

        if ($stmt_update->execute()) {
            echo "<script>alert('Usuario actualizado con éxito.'); window.location='ver_usuarios.php';</script>";
        } else {
            echo "Error al actualizar el usuario.";
        }

        $stmt_update->close();
    }
    $stmt->close();
} else {
    header("Location: ver_usuarios.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header>
        <h1>✏️ Editar Usuario</h1>
        <nav>
            <a href="index.php">🏠 Inicio</a>
            <a href="ver_usuarios.php">Usuarios</a>
            <a href="logout.php" class="cerrar">❌ Cerrar Sesión</a>
        </nav>
    </header>

    <section>
        <h2>Editar Usuario</h2>
        <form action="editar_usuario.php?id=<?php echo $usuario['id']; ?>" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
            
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" required>
            
            <label for="tipo">Tipo de Usuario:</label>
            <select id="tipo" name="tipo" required>
                <option value="cliente" <?php echo ($usuario['tipo'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                <option value="admin" <?php echo ($usuario['tipo'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            
            <button type="submit">Actualizar Usuario</button>
        </form>
    </section>
</body>
</html>
