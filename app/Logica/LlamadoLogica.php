<?php
require_once __DIR__ . '/../Persistencia/LlamadoPersistencia.php';
require_once __DIR__ . '/../Persistencia/postulacionPersistencia.php'; 

class LlamadoLogica {
    private $persistencia;

    public function __construct() {
        $this->persistencia = new LlamadoPersistencia();
    }

    public function listarLlamados() {
        return $this->persistencia->obtenerTodos();
    }

    public function listarLlamadosPorEmpresa($ci) {
        return $this->persistencia->obtenerPorEmpresaCI($ci);
    }

    public function registrarLlamado($ciEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $archivoImagen) {
        if (empty($titulo) || empty($desarrollo) || empty($fecha) || empty($vacantes)) {
            return ["exito" => false, "mensaje" => "Todos los campos obligatorios deben completarse."];
        }

        $rutaRelativaDB = 'static/img/llamados/default.jpg';

        if (isset($archivoImagen) && $archivoImagen['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($archivoImagen['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidas)) {
                return ["exito" => false, "mensaje" => "Formato de imagen no permitido."];
            }

            $nombreArchivo = 'llamado_' . time() . '.' . $ext;
            $carpetaDestino = __DIR__ . '/../../static/img/llamados/';

            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            if (move_uploaded_file($archivoImagen['tmp_name'], $carpetaDestino . $nombreArchivo)) {
                $rutaRelativaDB = 'static/img/llamados/' . $nombreArchivo;
            }
        }

        $exito = $this->persistencia->crearLlamado($ciEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $rutaRelativaDB);

        if ($exito) {
            return ["exito" => true, "mensaje" => "Llamado creado exitosamente."];
        } else {
            return ["exito" => false, "mensaje" => "Error al guardar el llamado en la base de datos."];
        }
    }
    public function modificarLlamado($idLlamado, $titulo, $desarrollo, $vacantes, $horario, $imagen) {
        return $this->persistencia->actualizarLlamado($idLlamado, $titulo, $desarrollo, $vacantes, $horario, $imagen);
    }

    public function borrarLlamado($id) {
        return $this->persistencia->eliminarLlamado($id);
    }

    public function verPostulantes($idLlamado) {
    $postPersistencia = new PostulacionPersistencia();
    return $postPersistencia->obtenerPostulantesPorLlamado($idLlamado);
    }

    public function obtenerPorEmpresaCI($ci) {
    return $this->persistencia->obtenerPorEmpresaCI($ci);
    }
}
?>