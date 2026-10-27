<?php
require_once __DIR__ . '/../Logica/usuarioLogica.php';

class UsuarioPresentacion {
    private $logica;

    public function __construct() {
        $this->logica = new UsuarioLogica();
    }

    public function cargarPerfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        $mensaje_error = "";
        $mensaje_exito = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre       = trim($_POST['nombre'] ?? '');
            $apellido     = trim($_POST['apellido'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $codigoPostal = trim($_POST['codigo_postal'] ?? '');
            $fotoSubida   = $_FILES['foto_perfil'] ?? null;

            $res = $this->logica->actualizarPerfil($_SESSION['usuario'], $nombre, $apellido, $email, $codigoPostal, $fotoSubida);

            if ($res['exito']) {
                $mensaje_exito = $res['mensaje'];
                $_SESSION['nombre']   = $nombre;
                $_SESSION['apellido'] = $apellido;
            } else {
                $mensaje_error = $res['mensaje'];
            }
        }

        $usuario = $this->logica->obtenerPorCI($_SESSION['usuario']);

        return [
            'usuario'       => $usuario,
            'mensaje_exito' => $mensaje_exito,
            'mensaje_error' => $mensaje_error
        ];
    }
}
?>