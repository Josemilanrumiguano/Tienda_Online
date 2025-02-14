<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

// Obtener los productos del carrito del usuario
$sql = "SELECT c.id AS carrito_id, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS total
        FROM carrito c
        JOIN productos p ON c.producto_id = p.id
        WHERE c.usuario_id = ?";
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
    <title>Mi Carrito - Tienda Dental</title>
    <link rel="stylesheet" href="estilos.css">
<style>
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

.carrito {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
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

input[type="number"] {
    width: 60px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
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

.total {
    text-align: right;
    padding: 20px;
    background-color: #f1f1f1;
    border-radius: 8px;
    margin-top: 20px;
}

.total h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #2c3e50;
}

.btn-pago {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #3498db;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.btn-pago:hover {
    background-color: #2980b9;
}

@media (max-width: 768px) {
    header h1 {
        font-size: 2rem;
    }

    nav a {
        display: block;
        margin: 10px 0;
    }

    .carrito {
        padding: 10px;
    }

    table th, table td {
        padding: 8px;
    }

    input[type="number"] {
        width: 50px;
    }

    button {
        padding: 6px 10px;
    }

    .total h3 {
        font-size: 1.2rem;
    }

    .btn-pago {
        padding: 8px 16px;
    }
}
</style>

</head>
<body>
    <header>
        <h1>🛍️ Mi Carrito</h1>
        <nav>
            <a href="index.php">🏠 Inicio</a>
            <a href="productos.php">🛒 Ver Productos</a>
            <a href="ver_pedidos.php">📦 Mis Pedidos</a>
            <a href="logout.php" class="cerrar">❌ Cerrar Sesión</a>
        </nav>
    </header>

    <section class="carrito">
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["nombre"]; ?></td>
                        <td>$<?php echo number_format($row["precio"], 2); ?></td>
                        <td>
                            <form action="actualizar_carrito.php" method="POST">
                                <input type="hidden" name="carrito_id" value="<?php echo $row["carrito_id"]; ?>">
                                <input type="number" name="cantidad" value="<?php echo $row["cantidad"]; ?>" min="1" required>
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($row["total"], 2); ?></td>
                        <td>
                            <form action="eliminar_carrito.php" method="POST">
                                <input type="hidden" name="carrito_id" value="<?php echo $row["carrito_id"]; ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <div class="total">
                <?php
                $sql_total = "SELECT SUM(p.precio * c.cantidad) AS total_carrito
                              FROM carrito c
                              JOIN productos p ON c.producto_id = p.id
                              WHERE c.usuario_id = ?";
                $stmt_total = $conn->prepare($sql_total);
                $stmt_total->bind_param("i", $usuario_id);
                $stmt_total->execute();
                $result_total = $stmt_total->get_result();
                $total = $result_total->fetch_assoc();
                ?>
                <h3>Total: $<?php echo number_format($total['total_carrito'], 2); ?></h3>
                <a href="procesar_pedido.php" class="btn-pago">Registrar Pedido</a>
                <a href="realizar_pago.php" class="btn-pago">Ir a Pagar</a>
            </div>
        <?php } else { ?>
            <p style="text-align: center; font-size: 1.5rem; color: #e74c3c; font-weight: bold;">🛒 Tu carrito está vacío</p>
        <?php } ?>
    </section>
</body>
</html>

<?php $stmt->close(); $conn->close(); ?>
