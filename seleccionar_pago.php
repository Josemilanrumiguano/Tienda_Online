<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION["usuario_id"])) {
    echo "Debes iniciar sesión para continuar.";
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$sql = "SELECT id, tipo, detalles FROM metodos_pago WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Método de Pago</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Selecciona tu Método de Pago</h2>
    <form action="procesar_pago.php" method="POST">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <input type="radio" name="metodo_pago" value="<?php echo $row["id"]; ?>" required>
            <?php echo ucfirst($row["tipo"]); ?> - <?php echo $row["detalles"]; ?><br>
        <?php } ?>
        <button type="submit">Pagar</button>
    </form>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
