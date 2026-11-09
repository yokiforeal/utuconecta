<?php
session_start();
require_once 'app/Logica/llamadoLogica.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

$llamadoLogica = new LlamadoLogica();
$ciEmpresa = $_SESSION['usuario'];

$misLlamados = $llamadoLogica->obtenerPorEmpresaCI($ciEmpresa);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'eliminar') {
            $idLlamado = intval($_POST['id_llamado']);
            $llamadoLogica->borrarLlamado($idLlamado);
            header("Location: mis_llamados.php");
            exit();
        } elseif ($_POST['accion'] === 'editar') {
            $idLlamado = intval($_POST['id_llamado']);
            $titulo = $_POST['titulo'];
            $desarrollo = $_POST['desarrollo'];
            $vacantes = intval($_POST['vacantes']);
            $horario = $_POST['horario'];
            $imagenActual = $_POST['imagen_actual'] ?? '';

            // subida de nueva imagen
            $rutaImagen = $imagenActual;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreFoto = time() . '_' . basename($_FILES['imagen']['name']);
                $dirDestino = 'static/img/llamados';
                if (!is_dir($dirDestino)) {
                    mkdir($dirDestino, 0777, true);
                }
                $rutaDestino = $dirDestino . $nombreFoto;
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $rutaImagen = $rutaDestino;
                }
            }

            $llamadoLogica->modificarLlamado($idLlamado, $titulo, $desarrollo, $vacantes, $horario, $rutaImagen);
            header("Location: mis_llamados.php");
            exit();
        }
    }
}
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

        <?php include 'header.php'; ?>

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

            <?php if (!empty($misLlamados)): ?>
                <?php foreach ($misLlamados as $llamado): ?>
                    
                    <div class="admin-form-card">
                        <h3 class="titulo-form-admin"><?php echo htmlspecialchars($llamado['Titulo']); ?></h3>
                        
                        <div class="tarjeta_cuerpo">
                            
                            <!-- MUESTRA LA IMAGEN ACTUAL DEL LLAMADO -->
                            <div class="post_imagen_div">
                                <?php 
                                    $fotoMostrar = !empty($llamado['Imagen']) ? $llamado['Imagen'] : 'static/img/default_llamado.png'; 
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
            if (file_exists('footer.php')) {
                include 'footer.php';
            }
        ?>

    </div>

   <script src="static/javascript/java_mis_llamados.js"></script>
   <script src="static/javascript/java_index.js"></script> 

</body>
</html>