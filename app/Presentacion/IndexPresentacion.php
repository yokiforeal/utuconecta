<?php
require_once __DIR__ . '/../Logica/LlamadoLogica.php';
require_once __DIR__ . '/../Logica/PostulacionLogica.php';

class IndexPresentacion {
    private $llamadoLogica;
    private $postulacionLogica;

    public function __construct() {
        $this->llamadoLogica = new LlamadoLogica();$this->postulacionLogica = new PostulacionLogica();
    }

    public function cargarPagina() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        // Si es empresa, redirigir a sus llamados
        if (isset($_SESSION['tipo_usuario']) &&$_SESSION['tipo_usuario'] === 'empresa') {
            header("Location: mis_llamados.php");
            exit();
        }

        $mensaje_accion = "";
        $error_accion = "";

        // Procesar peticiones POST (Postularse o Cancelar)
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $accion =$_POST['accion'] ?? '';
            $idLlamado = intval($_POST['id_llamado'] ?? 0);
            $ciUsuario =$_SESSION['usuario'];

            if ($accion === 'postular') {
                $nota = trim($_POST['nota'] ?? '');
                $expectativa = intval($_POST['expectativa_salarial'] ?? 0);
                $archivo =$_FILES['archivo_cv'] ?? null;

                $res =$this->postulacionLogica->postularse($ciUsuario,$idLlamado, $nota, $expectativa,$archivo);
                if ($res['exito']) {
                    $mensaje_accion =$res['mensaje'];
                } else {
                    $error_accion =$res['mensaje'];
                }
            } elseif ($accion === 'cancelar') {
                $res =$this->postulacionLogica->cancelar($ciUsuario,$idLlamado);
                if ($res['exito']) {
                    $mensaje_accion =$res['mensaje'];
                } else {
                    $error_accion =$res['mensaje'];
                }
            }
        }

        // Filtrar llamados si viene búsqueda por GET
        $busqueda =$_GET['buscar'] ?? '';
        if (!empty(trim($busqueda))) {$llamados = $this->llamadoLogica->buscarLlamados($busqueda);
        } else {
            $llamados =$this->llamadoLogica->listarLlamados();
        }

        return [
            'llamados'          => $llamados,
            'mensaje_accion'    => $mensaje_accion,
            'error_accion'      => $error_accion,
            'postulacionLogica' => $this->postulacionLogica,
            'busqueda'          => $busqueda
        ];
    }
}
?>