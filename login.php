<?php
session_start();
require_once 'conexion.php'; // función de la conexión

$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        
        // obtener conexión desde la función
        $conn = obtenerConexion();

        $stmt = $conn->prepare("SELECT CI, Nombre, Apellido, Password FROM usuarios WHERE CI = ?"); //sentencia sql para revisar los datos
        $stmt->bind_param("i", $username); // cambia el ? por la cedula, es por seguridad "i" hace que si o si se comporte como un entero para evitar ataques maliciosos xd
        $stmt->execute(); // ejecuta la sentencia preparada antes
        $resultado = $stmt->get_result(); // guarda en la variable los datos 

        if ($usuario = $resultado->fetch_assoc()) { // vuelve los datos "utilizables"
            if ($password === $usuario['Password']) { // verifica la password comparandola a la de la base de datos 
                // cada caja de sesion toma el valor del formulario
                $_SESSION['usuario'] = $usuario['CI']; 
                $_SESSION['nombre'] = $usuario['Nombre'];
                $_SESSION['apellido'] = $usuario['Apellido']; 

                header("Location: index.php");
                exit();
            } else {
                $mensaje_error = "Usuario o contraseña incorrectos";
            }
        } else {
            $mensaje_error = "Usuario o contraseña incorrectos";
        }

        $stmt->close();
        $conn->close();
    } else {
        $mensaje_error = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ANEP UTU</title>
    <link rel="stylesheet" href="static/styles/style_login_pc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap');
    </style>
</head>
<body>
    <div class="all-app">
        
        <?php include 'header.php'; ?>
        
        <div class="contenido_medio">
            <div class="parte_izquierda">
                <div>      
                    <img id="logoutu" src="static/img/logoutu.png" alt="Logo de Escuela Técnica">
                     
                    <?php if (!empty($mensaje_error)): ?>
                        <p style="color: red; text-align: center; margin-bottom: 40px; padding-top: 0">
                            <?php echo htmlspecialchars($mensaje_error); ?>
                        </p>
                    <?php endif; ?>

                    <form method="post" action="login.php" class="formulario-login">
                        <div class="username">
                            <input type="text" name="username" required placeholder="Cédula">
                        </div>

                        <div class="password">
                            <input type="password" name="password" required placeholder="Contraseña">
                        </div>

                        <div class="recordar">¿Olvidaste tu contraseña?</div>

                        <input type="submit" value="Iniciar">

                        <div class="registrarse">
                            <a href="register.php">Registrarse</a>    
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="parte_derecha">
                <div>
                    <img id="imagen_login" src="static/img/imagen_login.jpg" alt="Oficinista de Stock">
                </div>
            </div>        
        </div>

        <?php include 'footer.php'; ?>

    </div>    
</body>    
</html>
