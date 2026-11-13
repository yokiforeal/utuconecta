<?php
require_once __DIR__ . '/../Logica/llamadoLogica.php';

class LlamadoPresentacion {
    private $llamadoLogica;

    public function __construct() {
        $this->llamadoLogica = new LlamadoLogica();
    }

    public function gestionarPagina() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario']) || $_SESSION['tipo_usuario'] !== 'empresa') {
            header("Location: login.php");
            exit();
        }

        $ciEmpresa = $_SESSION['usuario'];
        $mensaje_exito = '';
        $mensaje_error = '';

        // Manejo limpio de peticiones POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['accion'])) {
                
                // CREAR NUEVO LLAMADO
                if ($_POST['accion'] === 'crear') {
                    $titulo = $_POST['titulo'] ?? '';
                    $desarrollo = $_POST['desarrollo'] ?? '';
                    $fecha = $_POST['fecha'] ?? date('Y-m-d');
                    $horario = $_POST['horario'] ?? '';
                    $vacantes = intval($_POST['vacantes'] ?? 1);
                    $archivoImagen = $_FILES['imagen'] ?? null;

                    $resultado = $this->llamadoLogica->registrarLlamado($ciEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $archivoImagen);
                    
                    if ($resultado['exito']) {
                        header("Location: mis_llamados.php");
                        exit();
                    } else {
                        $mensaje_error = $resultado['mensaje'];
                    }
                } 
                // ELIMINAR LLAMADO
                elseif ($_POST['accion'] === 'eliminar') {
                    $idLlamado = intval($_POST['id_llamado']);
                    $this->llamadoLogica->borrarLlamado($idLlamado);
                    header("Location: mis_llamados.php");
                    exit();
                } 
                // EDITAR LLAMADO EXISTENTE
                elseif ($_POST['accion'] === 'editar') {
                    $idLlamado = intval($_POST['id_llamado']);
                    $titulo = $_POST['titulo'];
                    $desarrollo = $_POST['desarrollo'];
                    $vacantes = intval($_POST['vacantes']);
                    $horario = $_POST['horario'];
                    $imagenActual = $_POST['imagen_actual'] ?? '';

                    $rutaImagen = $imagenActual;
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $nombreFoto = 'llamado_' . time() . '_' . basename($_FILES['imagen']['name']);
                        $dirDestino = __DIR__ . '/../../static/img/llamados/';
                        if (!is_dir($dirDestino)) {
                            mkdir($dirDestino, 0777, true);
                        }
                        $rutaDestino = $dirDestino . $nombreFoto;
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                            $rutaImagen = 'static/img/llamados/' . $nombreFoto;
                        }
                    }

                    $this->llamadoLogica->modificarLlamado($idLlamado, $titulo, $desarrollo, $vacantes, $horario, $rutaImagen);
                    header("Location: mis_llamados.php");
                    exit();
                }
            }
        }

         $misLlamados = $this->llamadoLogica->listarLlamadosPorEmpresa($ciEmpresa);

         return [
            'llamadoLogica' => $this->llamadoLogica,
            'mis_llamados'  => $misLlamados, // <-- Cambiado de 'misLlamados' a 'mis_llamados'
            'mensaje_exito' => $mensaje_exito,
            'mensaje_error' => $mensaje_error
        ];
    }
}
?>