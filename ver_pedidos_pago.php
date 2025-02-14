<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

// Obtener pedidos pendientes de pago
$sql = "SELECT p.id, p.total, p.fecha, p.estado FROM pedidos p WHERE p.usuario_id = ? AND p.estado = 'pendiente'";
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
    <title>Mis Pedidos Pendientes</title>
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

section {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    font-size: 2rem;
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
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

p {
    text-align: center;
    font-size: 1.2rem;
    color: #555;
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

    table th, table td {
        padding: 8px;
    }

    p {
        font-size: 1rem;
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

    p {
        font-size: 0.9rem;
    }
}
    </style>
</head>
<body>
    <header>
        <h1>🛒 Mis Pedidos Pendientes</h1>
        <nav>
            <a href="index.php">🏠 Inicio</a>
            <a href="ver_pedidos.php">Mis Pedidos</a>
            <a href="logout.php" class="cerrar">❌ Cerrar Sesión</a>
        </nav>
    </header>

    <section>
        <h2>Pedidos Pendientes de Pago</h2>
        <?php if ($result->num_rows > 0) { ?>
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
                        <a href="detalle_pedido.php?id=<?php echo $row['id']; ?>">Ver Detalle</a> |
                        <a href="realizar_pago.php?id=<?php echo $row['id']; ?>">Pagar</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No tienes pedidos pendientes de pago.</p>
        <?php } ?>
    </section>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
