<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión para ver los detalles del pedido.";
    exit();
}

if (!isset($_GET["id"])) {
    echo "Pedido no encontrado.";
    exit();
}

$pedido_id = $_GET["id"];

$sql = "SELECT p.nombre, dp.cantidad, dp.precio, (dp.cantidad * dp.precio) AS total
        FROM detalle_pedido dp
        JOIN productos p ON dp.producto_id = p.id
        WHERE dp.pedido_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido</title>
    <style>
        /* Estilos generales */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

/* Contenedor principal */
.container {
    max-width: 900px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Título */
h2 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 20px;
}

/* Tabla de pedidos */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

table th {
    background: #34495e;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
}

table tr:nth-child(even) {
    background: #f8f9fa;
}

table tr:hover {
    background: #ecf0f1;
}

/* Total del pedido */
.total-container {
    margin-top: 20px;
    padding: 15px;
    background: #eaf2f8;
    border-radius: 8px;
    text-align: right;
}

.total-container h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    font-weight: bold;
}

/* Botón de acción */
.btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    background: #1abc9c;
    color: #fff;
    font-size: 1rem;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #16a085;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 15px;
    }

    h2 {
        font-size: 1.8rem;
    }

    table th, table td {
        padding: 10px;
    }

    .btn {
        width: 100%;
        text-align: center;
    }
}


    </style>
</head>
<body>
    <h2>Detalle del Pedido #<?php echo $pedido_id; ?></h2>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
        </tr>
        <?php 
        $totalFinal = 0;
        while ($row = $result->fetch_assoc()) { 
            $totalFinal += $row["total"];
        ?>
        <tr>
            <td><?php echo $row["nombre"]; ?></td>
            <td><?php echo $row["cantidad"]; ?></td>
            <td>$<?php echo number_format($row["precio"], 2); ?></td>
            <td>$<?php echo number_format($row["total"], 2); ?></td>
        </tr>
        <?php } ?>
    </table>
    <h3>Total del Pedido: $<?php echo number_format($totalFinal, 2); ?></h3>

    <a href="ver_pedidos.php">🏠 Regresar</a>

    
</body>
</html>

<?php $stmt->close(); $conn->close(); ?>
