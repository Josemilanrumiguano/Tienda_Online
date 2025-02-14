<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Tienda Dental</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(135deg, #2c3e50, #1abc9c);
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
            transition: all 0.3s ease-in-out;
            padding: 10px 15px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .cerrar {
            background-color: #e74c3c;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .cerrar:hover {
            background-color: #c0392b;
        }

        .swiper {
            width: 100%;
            max-width: 1200px;
            height: 400px;
            margin: 30px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: center;
            
        }

        .bienvenida {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .bienvenida h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .bienvenida p {
            font-size: 1.2rem;
            color: #555;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }

            nav a {
                display: block;
                margin: 10px 0;
            }

            .bienvenida {
                padding: 15px;
            }

            .bienvenida h2 {
                font-size: 1.5rem;
            }

            .bienvenida p {
                font-size: 1rem;
            }

            .swiper {
                height: 250px;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 1.8rem;
            }

            .bienvenida h2 {
                font-size: 1.3rem;
            }

            .bienvenida p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenido, <?php echo $_SESSION["nombre"]; ?> </h1>
    <nav>
        <a href="productos.php">🛒 Ver Productos</a>
        <a href="ver_carrito.php">🛍️ Mi Carrito</a>
        <a href="ver_pedidos.php">📦 Mis Pedidos</a>
        <a href="ver_pedidos_pago.php">🛍️ Pagos Pendientes</a>
        <a href="logout.php" class="cerrar">❌ Cerrar Sesión</a>
    </nav>
</header>

<!-- Carrusel de Imágenes -->
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide"><img src="imagenes/indexjpg.jpg" alt="Promoción 1"></div>
        <div class="swiper-slide"><img src="imagenes/ejuague_bucal.png" alt="Promoción 2"></div>
        <div class="swiper-slide"><img src="imagenes/cepillo_dental.jpg" alt="Promoción 3"></div>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<section class="bienvenida">
    <h2>¡Explora nuestros productos de cuidado dental! 🦷✨</h2>
    <p>Tenemos lo mejor para tu salud bucal.</p>
</section>

<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
    });
</script>

</body>
</html>
