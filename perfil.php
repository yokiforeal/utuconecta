<?php
require_once 'app/Presentacion/usuarioPresentacion.php';

$presentacion = new UsuarioPresentacion();
$datosVista = $presentacion->cargarPerfil();

$usuario       = $datosVista['usuario'];
$mensaje_exito = $datosVista['mensaje_exito'];
$mensaje_error = $datosVista['mensaje_error'];

// Lógica para determinar la imagen a mostrar
$fotoBD = $usuario ? $usuario->getFotoPerfil() : null;
$rutaFotoMostrar = (!empty($fotoBD) && file_exists($fotoBD)) ? $fotoBD : 'static/img/logoutu.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - UTUConecta</title>
    <link rel="stylesheet" href="static/styles/style_index.css">
    <link rel="stylesheet" href="static/styles/style_perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="all-app">
        <?php include 'header.php'; ?>

        <main class="perfil-container">
            <section class="perfil-card">
                <a href="index.php">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <?php if (!empty($mensaje_exito)): ?>
                    <p style="color: green; text-align: center; margin-bottom: 15px; font-weight: bold;">
                        <?php echo htmlspecialchars($mensaje_exito); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($mensaje_error)): ?>
                    <p style="color: red; text-align: center; margin-bottom: 15px; font-weight: bold;">
                        <?php echo htmlspecialchars($mensaje_error); ?>
                    </p>
                <?php endif; ?>

                <form id="form-perfil" method="post" action="perfil.php" enctype="multipart/form-data">
                    
                    <div class="perfil-header">
                        <div class="avatar-wrapper">
                            <img id="img-perfil" src="<?php echo htmlspecialchars(($usuario && $usuario->getFotoPerfil()) ? $usuario->getFotoPerfil() : 'static/img/logoutu.png'); ?>" alt="Foto de Perfil">
                            
                            <label for="input-foto" class="btn-cambiar-foto" style="display: none;">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            <input type="file" name="foto_perfil" id="input-foto" accept="image/*" style="display: none;">
                        </div>
                        <h2><?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?></h2>
                        <p class="rol-tag">Postulante</p>
                    </div>

                    <div class="perfil-grid">
                        <div class="campo-grupo">
                            <label><i class="fa-solid fa-id-card"></i> Cédula de Identidad</label>
                            <input type="text" name="cedula" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>" readonly disabled>
                        </div>

                        <div class="campo-grupo">
                            <label><i class="fa-solid fa-user"></i> Nombre</label>
                            <input type="text" name="nombre" class="input-editable" value="<?php echo htmlspecialchars($usuario ? $usuario->getNombre() : ''); ?>" readonly>
                        </div>

                        <div class="campo-grupo">
                            <label><i class="fa-solid fa-user"></i> Apellido</label>
                            <input type="text" name="apellido" class="input-editable" value="<?php echo htmlspecialchars($usuario ? $usuario->getApellido() : ''); ?>" readonly>
                        </div>

                        <div class="campo-grupo">
                            <label><i class="fa-solid fa-envelope"></i> Correo Electrónico</label>
                            <input type="email" name="email" class="input-editable" value="<?php echo htmlspecialchars($usuario ? $usuario->getMail() : ''); ?>" readonly>
                        </div>

                        <div class="campo-grupo">
                            <label><i class="fa-solid fa-map-marker-alt"></i> Código Postal</label>
                            <input type="text" name="codigo_postal" class="input-editable" value="<?php echo htmlspecialchars($usuario ? $usuario->getCodigoPostal() : ''); ?>" readonly>
                        </div>
                    </div>

                    <div class="perfil-acciones">
                        <button type="button" id="btn-editar" class="btn-primario">
                            <i class="fa-solid fa-pen-to-square"></i> Editar Perfil
                        </button>
                        
                        <div id="acciones-edicion" style="display: none;">
                            <button type="submit" class="btn-guardar">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                            </button>
                            <button type="button" id="btn-cancelar" class="btn-cancelar">
                                <i class="fa-solid fa-xmark"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </main>

        <?php include 'footer.php'; ?>
    </div>


    <script src="static/javascript/perfil.js"></script>
</body>
</html>