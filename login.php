<?php
include 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contrasena = $_POST["contrasena"];

    $sql = "SELECT id, nombre, contrasena, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($contrasena, $row["contrasena"])) {
            $_SESSION["usuario_id"] = $row["id"];
            $_SESSION["nombre"] = $row["nombre"];
            $_SESSION["tipo"] = $row["tipo"];

            if ($row["tipo"] === "admin") {
                header("Location: index_administrador.php"); // Redirige al panel de administrador
            } else {
                header("Location: index.php"); // Redirige al menú de clientes
            }
            exit();
            
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo no registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
       body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            line-height: 1.6;
        }

        .contenedor {
            text-align: center;
        }

        h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-top: 20px; /* Separación entre el título y el formulario */
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #1abc9c;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1abc9c;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #16a085;
        }

        /* Mensajes de error */
        p {
            color: #e74c3c;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
            }

            form {
                padding: 20px;
            }

            input[type="email"],
            input[type="password"] {
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            h2 {
            color: #00796b;
            margin-bottom: 20px;
        }

            form {
                padding: 15px;
            }

            input[type="email"],
            input[type="password"] {
                font-size: 0.8rem;
            }

            button {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
<div class="contenedor">
        <h2>Iniciar Sesión</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
