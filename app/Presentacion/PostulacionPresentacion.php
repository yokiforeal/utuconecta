<?php
require_once __DIR__ . '/../Logica/PostulacionLogica.php';

class PostulacionPresentacion {
    private $postulacionLogica;

    public function __construct() {
        $this->postulacionLogica = new PostulacionLogica();
    }

    public function procesarAccionPostulacion() {
        $mensaje_accion = "";
        $error_accion = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_SESSION['usuario'])) {
                header("Location: login.php");
                exit();
            }

            $accion = $_POST['accion'] ?? '';
            $idLlamado = intval($_POST['id_llamado'] ?? 0);
            $ciUsuario = $_SESSION['usuario'];

            if ($accion === 'postular') {
                $nota = trim($_POST['nota'] ?? '');
                $expectativa = intval($_POST['expectativa_salarial'] ?? 0);
                $archivo = $_FILES['archivo_cv'] ?? null;

                $res = $this->postulacionLogica->postularse($ciUsuario, $idLlamado, $nota, $expectativa, $archivo);
                if ($res['exito']) {
                    $mensaje_accion = $res['mensaje'];
                } else {
                    $error_accion = $res['mensaje'];
                }
            } elseif ($accion === 'cancelar') {
                $res = $this->postulacionLogica->cancelar($ciUsuario, $idLlamado);
                if ($res['exito']) {
                    $mensaje_accion = $res['mensaje'];
                } else {
                    $error_accion = $res['mensaje'];
                }
            }
        }

        return [
            'mensaje_accion'    => $mensaje_accion,
            'error_accion'      => $error_accion,
            'postulacionLogica' => $this->postulacionLogica 
        ];
    }
}
?>