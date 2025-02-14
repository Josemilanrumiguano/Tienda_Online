<?php
session_start();
include 'conexion.php';

// Verificar si el usuario es admin
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != "admin") {
    header("Location: ver_usuarios.php");
    exit();
}

// Obtener todos los usuarios
$sql = "SELECT id, nombre, email, tipo, telefono FROM usuarios";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        /* Estilos generales */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

/* Encabezado */
header {
    background-color: #007bff;
    color: white;
    padding: 15px 0;
    text-align: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    font-size: 24px;
}

nav {
    margin-top: 10px;
}

nav a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 8px 15px;
    margin: 0 5px;
    border-radius: 5px;
    transition: background 0.3s;
}

nav a:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.cerrar {
    background-color: #dc3545;
    padding: 8px 15px;
    border-radius: 5px;
}

.cerrar:hover {
    background-color: #c82333;
}

/* Sección principal */
section {
    width: 80%;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #007bff;
}

/* Tabla de usuarios */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Botones de acción */
a {
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

a[href*="eliminar_usuario.php"] {
    color: #dc3545;
}

    </style>
</head>
<body>
    <header>
        <h1>👨‍💼 Gestión de Usuarios</h1>
        <nav>
            <a href="index_administrador.php">🏠 Inicio</a>
            <a href="ver_usuarios.php">Usuarios</a>
            <a href="logout.php" class="cerrar">❌ Cerrar Sesión</a>
        </nav>
    </header>

    <section>
        <h2>Lista de Usuarios</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["nombre"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo ucfirst($row["tipo"]); ?></td>
                <td><?php echo $row["telefono"]; ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?php echo $row['id']; ?>">Editar</a> |
                    <a href="eliminar_usuario.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>

<?php
$conn->close();
?>
