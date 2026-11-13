<?php
// Carga el archivo de presentación (capa lógica/controlador de la vista)
require_once 'app/Presentacion/indexPresentacion.php';

// Instancia la clase de presentación y obtiene los datos necesarios para la página
$presentacion = new IndexPresentacion();
$datosVista = $presentacion->cargarPagina();

// Extrae las variables del arreglo devuelto por el controlador para usarlas en el HTML
$llamados          = $datosVista['llamados'];
$mensaje_accion    = $datosVista['mensaje_accion'];
$error_accion      = $datosVista['error_accion'];
$postulacionLogica = $datosVista['postulacionLogica'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal - ANEP UTU</title>
    <!-- Hojas de estilos y librerías externas -->
    <link rel="stylesheet" href="static/styles/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap');
    </style>
</head>

<body>
    <div class="all_app"> 
        
        <!-- Incluye la barra de navegación superior (Header) -->
        <?php include __DIR__ . '/header.php'; ?>

        <div class="contenido_medio">    
            <!-- Panel superior del usuario (Buscador, notificaciones y menú desplegable de perfil) -->
            <div class="usercenter">
                <input type="search" placeholder="Buscar...">
                <i class="fa-regular fa-bell fa-user fa-lg" id="icono_campana" style="color: #ffffff;"></i>
                
                <div class="perfil_desplegable">
                    <i class="fa-regular fa-user fa-lg" id="perfil_boton" style="color: #ffffff;"></i>

                    <!-- Menú desplegable del perfil del usuario -->
                    <div class="menu_desplegable" id="menu_desplegable_perfil">
                        <a href="perfil.php" class="menu_desplegable_item">
                            <!-- Muestra el nombre y apellido del usuario de manera segura usando htmlspecialchars -->
                            <i class="fa-regular fa-id-card"></i> Perfil (<?php echo htmlspecialchars($_SESSION['nombre'] . " " . $_SESSION['apellido']); ?>)
                        </a>

                        <a href="#" class="menu_desplegable_item">
                            <i class="fa-solid fa-gear"></i> Configuración
                        </a>

                        <a href="logout.php" class="menu_desplegable_item">
                            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <!-- Muestra una alerta de éxito si hay un mensaje de acción proveniente del backend -->
           <?php if (!empty($mensaje_accion)): ?>
                <div class="alerta-exito"><?php echo $mensaje_accion; ?></div>
           <?php endif; ?>

           <!-- Muestra una alerta de error si ocurrió algún inconveniente -->
            <?php if (!empty($error_accion)): ?>
                <div class="alerta-error"><?php echo $error_accion; ?></div>
            <?php endif; ?>

            <!-- Comprueba si existen llamados para mostrarlos en pantalla -->
            <?php if (!empty($llamados)): ?>
                <?php foreach ($llamados as $llamado): ?>
                    <?php 
                        // Define una imagen por defecto si el llamado no tiene una registrada
                        $imagenPath = !empty($llamado['Imagen']) ? $llamado['Imagen'] : 'static/img/llamados/default.jpg';
                        // Verifica si el usuario actual ya se postuló a este llamado en específico
                        $yaPostulado = $postulacionLogica->verificarSiPostulo($_SESSION['usuario'], $llamado['ID_llamado']);
                    ?>
                    <!-- Contenedor individual para cada llamado -->
                    <div class="post">
                        <div class="post_imagen_div">
                            <img src="<?php echo htmlspecialchars($imagenPath); ?>" alt="Imagen del llamado" class="post_imagen">
                        </div>

                        <div class="post_texto">
                            <div class="post_info_interna">
                                <h2 class="titulo_post"><?php echo htmlspecialchars($llamado['Titulo']); ?></h2>
                                
                                <!-- Detalles del llamado (Fecha, Hora, Vacantes) -->
                                <div class="detalles_llamado">
                                    <span><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($llamado['Fecha']); ?></span>
                                    <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($llamado['Horario']); ?> hs</span>
                                    <span><i class="fa-solid fa-users"></i> Vacantes: <?php echo htmlspecialchars($llamado['Vacantes']); ?></span>
                                </div>

                                <p class="parrafo_post">
                                    <?php echo htmlspecialchars($llamado['Desarrollo']); ?>
                                </p>
                            </div>

                            <!-- Botones de acción según el estado de postulación del usuario -->
                            <div class="botones_postularse">
                                <?php if ($yaPostulado): ?>
                                    <!-- Si ya está postulado, muestra un botón para cancelar la postulación -->
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="accion" value="cancelar">
                                        <input type="hidden" name="id_llamado" value="<?php echo $llamado['ID_llamado']; ?>">
                                        <button type="submit" class="btn-cancelar" style="display:inline-block; background-color: #d9534f; color:white;">Cancelar Postulación</button>
                                    </form>
                                <?php else: ?>
                                    <!-- Si no está postulado, muestra un botón para abrir el modal de postulación -->
                                    <button class="btn_postularse" onclick="abrirModal(<?php echo $llamado['ID_llamado']; ?>)">Postularse</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Mensaje alternativo si no hay registros -->
                <p style="color: #2e4957; margin-top: 20px;">No hay llamados disponibles en este momento.</p>
            <?php endif; ?>

        </div>

        <!-- Incluye el pie de página (Footer) -->
        <?php include __DIR__ . '/footer.php'; ?>

    </div> 

    <!-- Ventana Modal HTML para enviar la postulación con datos adicionales (Nota, Expectativa salarial y Archivo CV) -->
    <div id="modalPostulacion" class="modal-postulacion">
        <form method="POST" enctype="multipart/form-data" class="modal-contenido">
            <h3>Enviar Postulación</h3>
            <input type="hidden" name="accion" value="postular">
            <!-- Campo oculto donde JavaScript asignará dinámicamente el ID del llamado seleccionado -->
            <input type="hidden" name="id_llamado" id="modal_id_llamado" value="">
            
            <div>
                <label>Nota / Presentación:</label>
                <textarea name="nota" placeholder="Breve descripción..."></textarea>
            </div>
            <div>
                <label>Expectativa Salarial ($):</label>
                <input type="number" name="expectativa_salarial" placeholder="Ej: 35000">
            </div>
            <div>
                <label>Archivo (CV / PDF):</label>
                <input type="file" name="archivo_cv">
            </div>
            <div style="display: flex; gap: 10px; margin-top: 10px;">
                <button type="submit" style="flex: 1; background: white; color: #2e4957; font-weight: bold; border: none; padding: 8px; border-radius: 5px; cursor: pointer;">Enviar</button>
                <button type="button" onclick="cerrarModal()" style="flex: 1; background: #c9302c; color: white; font-weight: bold; border: none; padding: 8px; border-radius: 5px; cursor: pointer;">Cerrar</button>
            </div>
        </form>
    </div>

    <!-- Script de JavaScript personalizado para manejar la interactividad (modal, menús, etc.) -->
    <script src="static/javascript/java_index.js"></script>
</body>
</html>