<?php
session_start();

// si no existe la sesion manda al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// conexion a la base de datos
require_once 'conexion.php';
$conn = obtenerConexion();

// Consulta para traer todos los llamados ordenados del mas reciente al mas antiguo
$sql = "SELECT ID_llamado, ID_empresa, Horario, Imagen, Desarrollo, Fecha, Titulo, Vacantes FROM llamados ORDER BY ID_llamado DESC";
$resultado = $conn->query($sql);
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
                        <a href="perfil.php" class="menu_desplegable_item">
                            <i class="fa-regular fa-id-card"></i> Perfil (<?php echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido']); ?>)
                        </a>

                        <a href="#" class="menu_desplegable_item">
                            <i class="fa-solid fa-gear"></i> Configuración
                        </a>

                        <!-- Redirige a logout.php para destruir la sesión -->
                        <a href="logout.php" class="menu_desplegable_item">
                            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <!-- BUCLE PARA MOSTRAR CADA LLAMADO REGISTRADO -->
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($llamado = $resultado->fetch_assoc()): ?>
                    <?php 
                        // Verificamos la imagen por si no tiene una guardada ponga una predeterminada y no se rompa todo xddd
                        $imagenPath = !empty($llamado['Imagen']) ? $llamado['Imagen'] : 'static/img/llamados/default.jpg';
                    ?>
                    <div class="post">
                        <!-- Contenedor de la imagen a la izquierda -->
                        <div class="post_imagen_div">
                            <img src="<?php echo htmlspecialchars($imagenPath); ?>" alt="Imagen del llamado" class="post_imagen">
                        </div>

                        <!-- Contenedor derecho que abarca texto, detalles y botones -->
                        <div class="post_texto">
                            <!-- Grupo superior (Título, Párrafo y Detalles) -->
                            <div class="post_info_interna">
                                <h2 class="titulo_post"><?php echo htmlspecialchars($llamado['Titulo']); ?></h2>
                                
                                <!-- Detalles adicionales del llamado (Fecha, Horario, Vacantes) -->
                                <div class="detalles_llamado">
                                    <span><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($llamado['Fecha']); ?></span>
                                    <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($llamado['Horario']); ?> hs</span>
                                    <span><i class="fa-solid fa-users"></i> Vacantes: <?php echo htmlspecialchars($llamado['Vacantes']); ?></span>
                                </div>

                                <p class="parrafo_post">
                                    <?php echo htmlspecialchars($llamado['Desarrollo']); ?>
                                </p>
                            </div>

                            <!-- Botones abajo del todo -->
                            <div class="botones_postularse">
                                <button class="btn-postularse">Postularse</button>
                                <button class="btn-cancelar">Cancelar</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #2e4957; margin-top: 20px;">No hay llamados disponibles en este momento.</p>
            <?php endif; ?>
            <?php $conn->close(); ?>

        </div>

        <?php include 'footer.php'; ?>

    </div> 
    
    <script src="static/javascript/java_index.js"></script>
</body>
</html>