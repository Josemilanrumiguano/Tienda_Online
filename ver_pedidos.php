<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión para ver tus pedidos.";
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$tipo_usuario = $_SESSION["tipo"]; // Para verificar si es admin

$sql = "SELECT id, total, fecha, estado FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    <style>
        /* estilos.css */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

header {
    background-color: #2c3e50;
    color: #fff;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
    font-weight: 600;
}

nav {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}

nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 15px;
    font-size: 1rem;
    transition: color 0.3s ease;
}

nav a:hover {
    color: #1abc9c;
}

.cerrar {
    color: #e74c3c;
}

.cerrar:hover {
    color: #c0392b;
}

h2 {
    text-align: center;
    font-size: 2rem;
    color: #2c3e50;
    margin-top: 30px;
    margin-bottom: 20px;
}

table {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #2c3e50;
    color: #fff;
    font-weight: 600;
}

table tr:hover {
    background-color: #f5f5f5;
}

a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #2980b9;
}

form {
    display: inline-block;
    margin: 0;
}

select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    margin-right: 10px;
}

button {
    padding: 8px 12px;
    background-color: #1abc9c;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #16a085;
}

/* Responsive Design */
@media (max-width: 768px) {
    header h1 {
        font-size: 2rem;
    }

    nav a {
        margin: 10px;
        font-size: 0.9rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    table {
        width: 100%;
        margin: 10px 0;
    }

    table th, table td {
        padding: 8px;
    }

    select {
        width: 100%;
        margin: 5px 0;
    }

    button {
        width: 100%;
        margin: 5px 0;
    }
}

@media (max-width: 480px) {
    header h1 {
        font-size: 1.8rem;
    }

    nav a {
        margin: 8px;
        font-size: 0.8rem;
    }

    h2 {
        font-size: 1.3rem;
    }

    table th, table td {
        padding: 6px;
        font-size: 0.9rem;
    }

    select {
        font-size: 0.9rem;
    }

    button {
        font-size: 0.9rem;
    }
}
    </style>
</head>
<body>
    <h2>Mis Pedidos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Total</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td>$<?php echo number_format($row["total"], 2); ?></td>
            <td><?php echo $row["fecha"]; ?></td>
            <td><?php echo ucfirst($row["estado"]); ?></td>
            <td>
                <a href="detalle_pedido.php?id=<?php echo $row['id']; ?>">Ver Detalle</a>

                <!-- Solo muestra el formulario si el usuario es admin -->
                <?php if ($tipo_usuario == "admin") { ?>
                <form action="actualizar_estado.php" method="POST">
                    <input type="hidden" name="pedido_id" value="<?php echo $row["id"]; ?>">
                    <select name="estado">
                        <option value="pendiente" <?php if ($row["estado"] == "pendiente") echo "selected"; ?>>Pendiente</option>
                        <option value="pagado" <?php if ($row["estado"] == "pagado") echo "selected"; ?>>Pagado</option>
                        <option value="enviado" <?php if ($row["estado"] == "enviado") echo "selected"; ?>>Enviado</option>
                        <option value="entregado" <?php if ($row["estado"] == "entregado") echo "selected"; ?>>Entregado</option>
                    </select>
                    <button type="submit">Actualizar</button>
                </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th><a href="index.php">Regresar</a></th>
        </tr>
    </table>
    
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
