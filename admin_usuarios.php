<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] != "admin") {
    echo "Acceso denegado.";
    exit();
}

$sql = "SELECT id, nombre, email, tipo FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Administrar Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["nombre"]; ?></td>
            <td><?php echo $row["email"]; ?></td>
            <td><?php echo ucfirst($row["tipo"]); ?></td>
            <td>
                <form action="actualizar_usuario.php" method="POST">
                    <input type="hidden" name="usuario_id" value="<?php echo $row["id"]; ?>">
                    <select name="tipo">
                        <option value="cliente" <?php if ($row["tipo"] == "cliente") echo "selected"; ?>>Cliente</option>
                        <option value="admin" <?php if ($row["tipo"] == "admin") echo "selected"; ?>>Administrador</option>
                    </select>
                    <button type="submit">Actualizar</button>
                </form>
                <form action="eliminar_usuario.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                    <input type="hidden" name="usuario_id" value="<?php echo $row["id"]; ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
