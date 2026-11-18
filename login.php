    <?php
    // Carga el archivo de presentación encargado de la lógica del usuario/login
    require_once __DIR__ . '/app/Presentacion/UsuarioPresentacion.php';

    // Instancia la clase de presentación del usuario
    $presentacion = new UsuarioPresentacion();

    // Ejecuta el método que procesa el inicio de sesión (validación de datos enviados por POST)
    $datos = $presentacion->procesarLogin();

    // Extrae el mensaje de error (si existió algún fallo al intentar iniciar sesión)
    $mensaje_error = $datos['mensaje_error'];
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión - ANEP UTU</title>
        <!-- Hojas de estilos y librerías externas -->
        <link rel="stylesheet" href="static/styles/style_login_pc.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap');
        </style>
    </head>
    <body>
        <div class="all-app">
            
            <!-- Incluye el Header -->
            <?php include 'header.php'; ?>
            
            <div class="contenido_medio">
                <!-- Sección izquierda: Contiene el logo, los mensajes de error y el formulario de acceso -->
                <div class="parte_izquierda">
                    <div>    
                        <!-- Logo institucional o de la escuela técnica -->
                        <img id="logoutu" src="static/img/logoutu.png" alt="Logo de Escuela Técnica">
                        
                        <!-- Muestra una alerta visual si hay un mensaje de error devuelto por el controlador -->
                        <?php if (!empty($mensaje_error)): ?>
                            <p style="color: red; text-align: center; margin-bottom: 40px; padding-top: 0">
                                <!-- Se utiliza htmlspecialchars para prevenir ataques de Cross-Site Scripting (XSS) -->
                                <?php echo htmlspecialchars($mensaje_error); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Formulario de login que se envía a sí mismo (login.php) mediante el método POST -->
                        <form method="post" action="login.php" class="formulario-login">
                            <div class="username">
                                <!-- Campo para ingresar la Cédula (identificador de usuario) -->
                                <input type="text" name="username" required placeholder="Cédula">
                            </div>

                            <div class="password">
                                <!-- Campo para ingresar la contraseña (oculto por seguridad) -->
                                <input type="password" name="password" required placeholder="Contraseña">
                            </div>

                            <!-- Enlace estático para recuperar contraseña (futura funcionalidad) -->
                            <div class="recordar">¿Olvidaste tu contraseña?</div>

                            <!-- Botón de envío del formulario -->
                            <input type="submit" value="Iniciar">

                            <!-- Enlace hacia la vista o página de registro de nuevos usuarios -->
                            <div class="registrarse">
                                <a href="register.php">Registrarse</a>    
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sección derecha: Espacio reservado para una imagen ilustrativa o institucional -->
                <div class="parte_derecha">
                    <div>
                        <img id="imagen_login" src="static/img/imagen_login.jpg" alt="Oficinista de Stock">
                    </div>
                </div>      
            </div>

            <!-- Incluye el Footer -->
            <?php include 'footer.php'; ?>

        </div>    
    </body>    
    </html>