<?php
$host = "localhost";
$user = "root";  // Cambia si tienes otro usuario en MySQL
$password = "";
$dbname = "tienda_dental";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
