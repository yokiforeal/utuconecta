<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'empresa') {
    header("Location: mis_llamados.php");
    exit();
}

require_once 'app/Presentacion/LlamadoPresentacion.php';
require_once 'app/Presentacion/PostulacionPresentacion.php';

$llamadoPresentacion = new LlamadoPresentacion();
$postulacionPresentacion = new PostulacionPresentacion();

$datosPostulacion  = $postulacionPresentacion->procesarAccionPostulacion();
$mensaje_accion    = $datosPostulacion['mensaje_accion'];
$error_accion      = $datosPostulacion['error_accion'];
$postulacionLogica = $datosPostulacion['postulacionLogica'];

$datosLlamados = $llamadoPresentacion->obtenerCatalogoLlamados();
$llamados      = $datosLlamados['llamados'];
$busqueda      = $datosLlamados['busqueda'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal - ANEP UTU</title>
    <link rel="stylesheet" href="static/styles/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap');
    </style>
</head>
<body>
    <div class="all_app"> 
        
        <?php include __DIR__ . '/header.php'; ?>

        <div class="contenido_medio">    
            <div class="usercenter">
                <form method="GET" action="index.php" style="display: flex; align-items: center; width: 85%;">
                    <input type="search" name="buscar" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>" style="color: #2e4957; padding-left: 10px;">
                </form>

                <i class="fa-regular fa-bell fa-lg" id="icono_campana" style="color: #ffffff;"></i>
    
                <div class="perfil_desplegable">
                    <i class="fa-regular fa-user fa-lg" id="perfil_boton" style="color: #ffffff;"></i>

                    <div class="menu_desplegable" id="menu_desplegable_perfil">
                        <a href="perfil.php" class="menu_desplegable_item">
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

            <?php if (!empty($mensaje_accion)): ?>
                <div class="alerta-exito"><?php echo htmlspecialchars($mensaje_accion); ?></div>
            <?php endif; ?>

            <?php if (!empty($error_accion)): ?>
                <div class="alerta-error"><?php echo htmlspecialchars($error_accion); ?></div>
            <?php endif; ?>

            <?php if (!empty($llamados)): ?>
                <?php foreach ($llamados as $llamado): ?>
                    <?php 
                        $imagenPath = !empty($llamado->getImagen()) ? $llamado->getImagen() : 'static/img/llamados/default.jpg';
                        $yaPostulado = $postulacionLogica->verificarSiPostulo($_SESSION['usuario'], $llamado->getIdLlamado());
                    ?>
                    <div class="post">
                        <div class="post_imagen_div">
                            <img src="<?php echo htmlspecialchars($imagenPath); ?>" alt="Imagen del llamado" class="post_imagen">
                        </div>

                        <div class="post_texto">
                            <div class="post_info_interna">
                                <h2 class="titulo_post"><?php echo htmlspecialchars($llamado->getTitulo()); ?></h2>
                                
                                <div class="detalles_llamado">
                                    <span><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($llamado->getFecha()); ?></span>
                                    <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($llamado->getHorario()); ?> hs</span>
                                    <span><i class="fa-solid fa-users"></i> Vacantes: <?php echo htmlspecialchars($llamado->getVacantes()); ?></span>
                                </div>

                                <p class="parrafo_post">
                                    <?php echo htmlspecialchars($llamado->getDesarrollo()); ?>
                                </p>
                            </div>

                            <div class="botones_postularse">
                                <?php if ($yaPostulado): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="accion" value="cancelar">
                                        <input type="hidden" name="id_llamado" value="<?php echo $llamado->getIdLlamado(); ?>">
                                        <button type="submit" class="btn-cancelar" style="display:inline-block; background-color: #d9534f; color:white;">Cancelar Postulación</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn_postularse" onclick="abrirModal(<?php echo $llamado->getIdLlamado(); ?>)">Postularse</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #2e4957; margin-top: 20px;">No se encontraron llamados disponibles.</p>
            <?php endif; ?>

        </div>

        <?php include __DIR__ . '/footer.php'; ?>

    </div> 

    <div id="modalPostulacion" class="modal-postulacion">
        <form method="POST" enctype="multipart/form-data" class="modal-contenido">
            <h3>Enviar Postulación</h3>
            <input type="hidden" name="accion" value="postular">
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

    <script src="static/javascript/java_index.js"></script>
</body>
</html>