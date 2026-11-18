<?php
session_start();

// Cargar el controlador de presentación de usuarios
require_once 'app/Presentacion/UsuarioPresentacion.php';

$usuarioPresentacion = new UsuarioPresentacion();
$resultadoRegistro = $usuarioPresentacion->procesarRegistro();

$mensaje_exito = $resultadoRegistro['mensaje_exito'] ?? '';
$mensaje_error = $resultadoRegistro['mensaje_error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - ANEP UTU</title>
    <link rel="stylesheet" href="static/styles/style_register_pc.css">
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
                     
                    <!-- Contenedor ordenado para las alertas de éxito o error -->
                    <div class="contenedor-alertas">
                        <?php if (!empty($mensaje_exito)): ?>
                            <div class="alerta-exito"><?php echo htmlspecialchars($mensaje_exito); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($mensaje_error)): ?>
                            <div class="alerta-error"><?php echo htmlspecialchars($mensaje_error); ?></div>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="register.php" class="formulario-login">
                        <div class="nombre">
                            <input type="text" name="nombre" required placeholder="Nombre">
                        </div>
                        <div class="apellido">
                            <input type="text" name="apellido" required placeholder="Apellido">
                        </div>
                        <div class="cedula">
                            <input type="text" name="ci" required placeholder="Cédula">
                        </div>
                        <div class="password">
                            <input type="password" name="password" required placeholder="Contraseña">
                        </div>
                        <div class="Teléfono">
                            <input type="text" name="telefono" placeholder="Teléfono">
                        </div>
                        <div class="Email">
                            <input type="email" name="mail" required placeholder="Email">
                        </div>
                        <div class="nacimiento">
                            <input type="date" name="nacimiento" placeholder="Fecha de nacimiento">
                        </div>
                        <div class="localidad">
                            <input type="text" name="localidad" placeholder="Localidad">
                        </div>
                        <div class="Código Postal" id="codpostal">
                            <input type="text" name="codigo_postal" placeholder="Código Postal">
                        </div>
                        
                        <input type="submit" value="Registrarse">
                    </form>

                </div>
            </div>
            
            <div class="parte_derecha">
                <div>
                    <img id="imagen_login" src="static/img/register.jpg" alt="Oficinista de Stock">
                </div>
            </div>        
        </div>
        <div id="rectangulo-azul-abajo" class="rectangulo-azul"></div>
    </div>    
</body>    
</html>