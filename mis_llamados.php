<?php
require_once 'app/Presentacion/LlamadoPresentacion.php';

$presentacion = new LlamadoPresentacion();
$datosVista = $presentacion->gestionarPagina();

$llamadoLogica = $datosVista['llamadoLogica'];
$misLlamados   = $datosVista['mis_llamados'];
$mensaje_exito = $datosVista['mensaje_exito'];
$mensaje_error = $datosVista['mensaje_error'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Llamados - UTU Conecta</title>
    <link rel="stylesheet" href="static/styles/style_mis_llamados.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="all_app">

        <?php include __DIR__ . '/header.php'; ?>

        <div class="contenido_medio">

            <div class="usercenter">
                <input type="search" placeholder="Buscar...">
                <i class="fa-regular fa-bell fa-lg" id="icono_campana" style="color: #ffffff;"></i>
                
                <div class="perfil_desplegable">
                    <i class="fa-regular fa-user fa-lg" id="perfil_boton" style="color: #ffffff;"></i>

                    <div class="menu_desplegable" id="menu_desplegable_perfil">
                        <a href="perfil.php" class="menu_desplegable_item">
                            <i class="fa-regular fa-id-card"></i> Perfil (<?php echo htmlspecialchars(($_SESSION['nombre'] ?? '') . " " . ($_SESSION['apellido'] ?? '')); ?>)
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

            <?php if (!empty($mensaje_exito)): ?>
                <div class="alerta-exito" style="color: #28a745; font-weight: bold; margin-bottom: 15px;"><?php echo htmlspecialchars($mensaje_exito); ?></div>
            <?php endif; ?>

            <?php if (!empty($mensaje_error)): ?>
                <div class="alerta-error" style="color: #d9534f; font-weight: bold; margin-bottom: 15px;"><?php echo htmlspecialchars($mensaje_error); ?></div>
            <?php endif; ?>

            <!-- TARJETA FORMULARIO PARA CREAR NUEVO LLAMADO -->
            <div class="admin-form-card">
                <h3 class="titulo-form-admin"> Publicar Nuevo Llamado</h3>
                
                <div class="tarjeta_cuerpo">
                    <div class="post_imagen_div">
                        <img src="static/img/llamados/default.jpg" alt="Imagen por defecto" class="post_imagen">
                    </div>

                    <form method="POST" enctype="multipart/form-data" class="form-crear-llamado">
                        <input type="hidden" name="accion" value="crear">
                        
                        <div class="form-grupo">
                            <label>Imagen del Llamado:</label>
                            <input type="file" name="imagen" accept="image/*" required>
                        </div>

                        <div class="form-grupo">
                            <label>Título:</label>
                            <input type="text" name="titulo" placeholder="Ej: Desarrollador Web PHP" required>
                        </div>
                        
                        <div class="form-grupo">
                            <label>Desarrollo:</label>
                            <textarea name="desarrollo" rows="3" placeholder="Detalles y requisitos del puesto..." required></textarea>
                        </div>
                        
                        <div class="form-row-triple">
                            <div class="form-grupo">
                                <label>Vacantes:</label>
                                <input type="number" name="vacantes" value="1" min="1" required>
                            </div>
                            
                            <div class="form-grupo">
                                <label>Horario:</label>
                                <input type="text" name="horario" placeholder="Ej: 09:00 - 17:00" required>
                            </div>

                            <div class="form-grupo">
                                <label>Fecha:</label>
                                <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <button type="submit" class="btn_guardar">Publicar Llamado</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- LISTADO DE LLAMADOS DE LA EMPRESA -->
            <?php if (!empty($misLlamados)): ?>
                <?php foreach ($misLlamados as $llamado): ?>
                    
                    <div class="admin-form-card">
                        <h3 class="titulo-form-admin"><?php echo htmlspecialchars($llamado['Titulo']); ?></h3>
                        
                        <div class="tarjeta_cuerpo">
                            
                            <!-- MUESTRA LA IMAGEN ACTUAL DEL LLAMADO -->
                            <div class="post_imagen_div">
                                <?php 
                                    $fotoMostrar = !empty($llamado['Imagen']) ? $llamado['Imagen'] : 'static/img/llamados/default.jpg'; 
                                ?>
                                <img src="<?php echo htmlspecialchars($fotoMostrar); ?>" alt="Foto del llamado" class="post_imagen">
                            </div>

                            <!-- FORMULARIO DE EDICIÓN CON CARGA DE ARCHIVO -->
                            <form method="POST" enctype="multipart/form-data" class="form-crear-llamado">
                                <input type="hidden" name="accion" value="editar">
                                <input type="hidden" name="id_llamado" value="<?php echo $llamado['ID_llamado']; ?>">
                                <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($llamado['Imagen'] ?? ''); ?>">
                                
                                <div class="form-grupo">
                                    <label>Cambiar Imagen:</label>
                                    <input type="file" name="imagen" accept="image/*">
                                </div>

                                <div class="form-grupo">
                                    <label>Título:</label>
                                    <input type="text" name="titulo" value="<?php echo htmlspecialchars($llamado['Titulo']); ?>" required>
                                </div>
                                
                                <div class="form-grupo">
                                    <label>Desarrollo:</label>
                                    <textarea name="desarrollo" rows="3" required><?php echo htmlspecialchars($llamado['Desarrollo']); ?></textarea>
                                </div>
                                
                                <div class="form-row-triple">
                                    <div class="form-grupo">
                                        <label>Vacantes:</label>
                                        <input type="number" name="vacantes" value="<?php echo $llamado['Vacantes']; ?>" required>
                                    </div>
                                    
                                    <div class="form-grupo">
                                        <label>Horario:</label>
                                        <input type="text" name="horario" value="<?php echo htmlspecialchars($llamado['Horario']); ?>" required>
                                    </div>
                                </div>
                                
                                <div style="display: flex; gap: 10px; margin-top: 5px; align-items: center;">
                                    <button type="submit" class="btn_guardar">Guardar Cambios</button>
                            </form>

                                    <form method="POST" style="margin: 0;">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_llamado" value="<?php echo $llamado['ID_llamado']; ?>">
                                        <button type="submit" class="btn_eliminar" onclick="return confirm('¿Estás seguro de eliminar este llamado y todas sus postulaciones?');">Eliminar Llamado</button>
                                    </form>
                                </div>
                        </div>

                        <!-- SECCIÓN POSTULANTES -->
                        <div class="seccion-postulantes">
                            <h4 style="margin-top: 0; color: #ffffff; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 5px;">Postulantes Inscriptos:</h4>
                            <?php 
                                $postulantes = $llamadoLogica->verPostulantes($llamado['ID_llamado']);
                            ?>
                            <?php if (!empty($postulantes)): ?>
                                <ul>
                                    <?php foreach ($postulantes as $p): ?>
                                        <li>
                                            <strong><?php echo htmlspecialchars($p['Nombre'] . " " . $p['Apellido']); ?></strong> 
                                            (CI: <?php echo $p['CI']; ?>) <br>
                                            Expectativa Salarial: $<?php echo htmlspecialchars($p['Expectativa_salarial']); ?> 
                                            <?php if (!empty($p['Nota'])): ?>
                                                <br><em>Nota: "<?php echo htmlspecialchars($p['Nota']); ?>"</em>
                                            <?php endif; ?>
                                            <br>
                                            <?php if (!empty($p['Archivos'])): ?>
                                                <a href="<?php echo htmlspecialchars($p['Archivos']); ?>" target="_blank" class="cv-link">Ver Archivo / CV Adjunto</a>
                                            <?php else: ?>
                                                <span style="color: #cbd5e1;">Sin archivo adjunto</span>
                                            <?php endif; ?>
                                        </li>
                                        <hr style="border:0; border-top:1px dashed rgba(255,255,255,0.2); margin: 8px 0;">
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p style="color: #cbd5e1; margin: 0; font-size: 0.9rem;">Aún no hay postulantes para este llamado.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="admin-form-card" style="text-align: center;">
                    <p style="margin: 0; font-size: 1rem;">No tienes llamados publicados actualmente.</p>
                </div>
            <?php endif; ?>

        </div>

        <?php 
            if (file_exists(__DIR__ . '/footer.php')) {
                include __DIR__ . '/footer.php';
            }
        ?>

    </div>

   <script src="static/javascript/java_index.js"></script> 

</body>
</html>