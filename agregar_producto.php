<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];

    // Manejo de la imagen
    $imagenNombre = $_FILES["imagen"]["name"];
    $imagenTmp = $_FILES["imagen"]["tmp_name"];
    $carpetaDestino = "imagenes/";

    // Crear carpeta si no existe
    if (!file_exists($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $rutaImagen = $carpetaDestino . basename($imagenNombre);
    
    if (move_uploaded_file($imagenTmp, $rutaImagen)) {
        // Guardar en la base de datos
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $rutaImagen);

        if ($stmt->execute()) {
            echo "Producto agregado correctamente.";
        } else {
            echo "Error al agregar el producto.";
        }
        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }

    $conn->close();
}
?>
