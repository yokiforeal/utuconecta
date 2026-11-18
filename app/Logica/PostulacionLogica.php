<?php
require_once __DIR__ . '/../Persistencia/PostulacionPersistencia.php';

class PostulacionLogica {
    private $persistencia;

    public function __construct() {
        $this->persistencia = new PostulacionPersistencia();
    }

    public function postularse($ci, $idLlamado, $nota, $expectativa, $archivoSubido) {
        if (empty($idLlamado) || empty($ci)) {
            return ["exito" => false, "mensaje" => "Datos incompletos para la postulación."];
        }

        if ($this->persistencia->yaSePostulo($ci, $idLlamado)) {
            return ["exito" => false, "mensaje" => "Ya te has postulado a este llamado anteriormente."];
        }

        $rutaArchivo = null;
        if (isset($archivoSubido) && $archivoSubido['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($archivoSubido['name'], PATHINFO_EXTENSION));
            $permitidas = ['pdf', 'doc', 'docx', 'jpg', 'png'];

            if (!in_array($ext, $permitidas)) {
                return ["exito" => false, "mensaje" => "Formato de archivo no permitido."];
            }

            $nombreArchivo = 'post_' . $ci . '_' . $idLlamado . '_' . time() . '.' . $ext;
            $carpetaDestino = __DIR__ . '/../../static/cv/';

            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            if (move_uploaded_file($archivoSubido['tmp_name'], $carpetaDestino . $nombreArchivo)) {
                $rutaArchivo = 'static/archivos_postulaciones/' . $nombreArchivo;
            }
        }

        $exito = $this->persistencia->registrarPostulacion($ci, $idLlamado, $nota, $expectativa, $rutaArchivo);

        if ($exito) {
            return ["exito" => true, "mensaje" => "¡Te has postulado con éxito!"];
        } else {
            return ["exito" => false, "mensaje" => "Error al registrar la postulación en la base de datos."];
        }
    }

    public function cancelar($ci, $idLlamado) {
        if ($this->persistencia->cancelarPostulacion($ci, $idLlamado)) {
            return ["exito" => true, "mensaje" => "Has cancelado tu postulación."];
        } else {
            return ["exito" => false, "mensaje" => "No se pudo cancelar la postulación."];
        }           
    }

    public function verificarSiPostulo($ci, $idLlamado) {
        return $this->persistencia->yaSePostulo($ci, $idLlamado);
    }
}
?>