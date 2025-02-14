<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

// Obtener productos de la base de datos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Tienda Dental</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    color: #333;
}

/* Encabezado */
header {
    background: #0077cc;
    color: white;
    padding: 15px;
    text-align: center;
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
    margin: 0 15px;
    font-weight: bold;
}

nav a:hover {
    text-decoration: underline;
}

.cerrar {
    background: #ff4444;
    padding: 8px 12px;
    border-radius: 5px;
}

.cerrar:hover {
    background: #cc0000;
}

/* Sección de productos */
.productos {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
}

.producto {
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin: 15px;
    padding: 20px;
    width: 250px;
    text-align: center;
    transition: transform 0.3s ease;
}

.producto:hover {
    transform: scale(1.05);
}

.producto img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

.producto h3 {
    font-size: 18px;
    margin: 10px 0;
}

.producto p {
    font-size: 14px;
    color: #555;
}

.producto strong {
    color: #28a745;
    font-size: 16px;
}

/* Botón agregar al carrito */
button {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    margin-top: 10px;
    transition: background 0.3s ease;
}

button:hover {
    background: #218838;
}

/* Formulario cantidad */
input[type="number"] {
    width: 50px;
    padding: 5px;
    margin: 5px 0;
    text-align: center;
}

    </style>
</head>
<body>
    <header>
        <h1>🛒 Nuestros Productos</h1>
        <nav>
            <a href="index.php">🏠 Inicio</a>
            <a href="ver_carrito.php">🛍️ Ver Carrito</a>
            <a href="ver_pedidos.php">📦 Mis Pedidos</a>
            <a href="logout.php" class="cerrar">❌ Cerrar Sesión</a>
        </nav>
    </header>

    <section class="productos">
        <?php while ($producto = $result->fetch_assoc()) { ?>
            <div class="producto">
                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
                <h3><?php echo $producto['nombre']; ?></h3>
                <p><?php echo $producto['descripcion']; ?></p>
                <p><strong>💲 <?php echo number_format($producto['precio'], 2); ?></strong></p>
                <form action="agregar_carrito.php" method="POST">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <label>Cantidad:</label>
                    <input type="number" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" required>
                    <button type="submit">🛒 Agregar al Carrito</button>
                </form>
            </div>
        <?php } ?>
    </section>
</body>
</html>

<?php $conn->close(); ?>
