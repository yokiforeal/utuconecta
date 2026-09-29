<?php
session_start();
$mensaje_error = "";

// procesamiento de datos al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // harcodeada historica hasta tener la base de datos
    if ($username === "user" && $password === "user") {
        $_SESSION['usuario'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $mensaje_error = "Usuario o contraseña incorrectos";
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
                        <p style="color: red; text-align: center; margin-bottom: 10px;">
                            <?php echo htmlspecialchars($mensaje_error); ?>
                        </p>
                    <?php endif; ?>

                    <form method="post" action="login.php" class="formulario-login">
                        <div class="username">
                            <input type="text" name="username" required placeholder="Nombre de Usuario">
                        </div>

                        <div class="password">
                            <input type="password" name="password" required placeholder="Contraseña">
                        </div>

                        <div class="recordar">¿Olvidaste tu contraseña?</div>

                        <input type="submit" value="Iniciar">

                        <div class="registrarse">
                            <a href="register.html">Registrarse</a>    
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
