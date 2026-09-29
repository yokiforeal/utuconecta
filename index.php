<?php
session_start();

// Si no existe la sesion manda al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal - ANEP UTU</title>
    <!-- Estilos específicos de la página principal -->
    <link rel="stylesheet" href="static/styles/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap');
    </style>
</head>

<body>
    <!-- CONTENEDOR DE TODA LA APLICACIÓN WEB -->
    <div class="all_app"> 
        
        <?php include 'header.php'; ?>

        <div class="contenido_medio">    
            <div class="usercenter">
                <input type="search" placeholder="Buscar...">
                <i class="fa-regular fa-bell fa-user fa-lg" id="icono_campana" style="color: #ffffff;"></i>
                
                <div class="perfil_desplegable">
                    <i class="fa-regular fa-user fa-lg" id="perfil_boton" style="color: #ffffff;"></i>

                    <div class="menu_desplegable" id="menu_desplegable_perfil">
                        <a href="#" class="menu_desplegable_item">
                            <i class="fa-regular fa-id-card"></i> Perfil (<?php echo htmlspecialchars($_SESSION['usuario']); ?>)
                        </a>

                        <a href="#" class="menu_desplegable_item">
                            <i class="fa-solid fa-gear"></i> Configuración
                        </a>

                        <!-- Redirige a logout.php para destruir la sesión correctamente -->
                        <a href="logout.php" class="menu_desplegable_item">
                            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <div class="post">
                <div class="post_imagen_div">
                    <img src="static/img/post_prueba_img.webp" alt="Imagen de Prueba" class="post_imagen">
                </div>
                
                <div class="post_texto">
                    <h1 class="titulo_post"> Título </h1>
                    <p class="parrafo_post">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London, took a 1914 Cicero translation and scrambled it to make dummy text for Letraset's Body Type sheets.</p>
                </div>
            </div>
            
            <div class="post"></div>
            <div class="post"></div>
            <div class="post"></div>
            <div class="post"></div>
        </div>

        <?php include 'footer.php'; ?>

    </div> 
    
    <script src="static/javascript/java_index.js"></script>
</body>
</html>