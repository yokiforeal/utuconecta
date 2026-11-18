<?php
require_once __DIR__ . '/../Logica/UsuarioLogica.php';
require_once __DIR__ . '/../../conexion.php';

class UsuarioPresentacion {
    private $logica;

    public function __construct() {
        $this->logica = new UsuarioLogica();
    }

    public function procesarLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $mensaje_error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $ci = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $resultado = $this->logica->login($ci, $password);

            if ($resultado['exito']) {
                $_SESSION['usuario'] = $resultado['usuario']->getCi();
                $_SESSION['nombre'] = $resultado['usuario']->getNombre();
                $_SESSION['apellido'] = $resultado['usuario']->getApellido();
                $_SESSION['tipo_usuario'] = $resultado['tipo'];

                if ($resultado['tipo'] === 'empresa') {
                    $_SESSION['id_empresa'] = $resultado['id_empresa'];
                    $_SESSION['nombre_empresa'] = $resultado['nombre_empresa'];
                    header("Location: mis_llamados.php");
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                $mensaje_error = $resultado['mensaje'];
            }
        }

        return ['mensaje_error' => $mensaje_error];
    }

    public function procesarRegistro() {
        $mensaje_error = "";
        $mensaje_exito = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre       = trim($_POST['nombre'] ?? '');
            $apellido     = trim($_POST['apellido'] ?? '');
            $cedula       = trim($_POST['ci'] ?? '');
            $password     = trim($_POST['password'] ?? '');
            $email        = trim($_POST['mail'] ?? '');
            $localidad    = trim($_POST['localidad'] ?? '');
            $codigoPostal = trim($_POST['codigo_postal'] ?? '');
            
            $tipoRegistro  = $_POST['tipo_usuario'] ?? 'postulante';
            $esEmpresa     = ($tipoRegistro === 'empresa');
            $nombreEmpresa = trim($_POST['nombre_empresa'] ?? '');

            $res = $this->logica->registrarUsuario($cedula, $nombre, $apellido, $email, $codigoPostal, $password, $localidad, $esEmpresa, $nombreEmpresa);

            if ($res['exito']) {
                $mensaje_exito = $res['mensaje'];
                header("Refresh: 2; URL=login.php");
            } else {
                $mensaje_error = $res['mensaje'];
            }
        }

        return [
            'mensaje_exito' => $mensaje_exito,
            'mensaje_error' => $mensaje_error
        ];
    }

    public function cargarPerfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $mensaje_error = "";
        $mensaje_exito = "";
        $usuario = null;

        if (!isset($_SESSION['usuario'])) {
            header("Location: login.php");
            exit();
        }

        $ci = $_SESSION['usuario'];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre       = trim($_POST['nombre'] ?? '');
            $apellido     = trim($_POST['apellido'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $codigoPostal = trim($_POST['codigo_postal'] ?? '');
            $archivoFoto  = $_FILES['foto_perfil'] ?? null;

            $resultadoActualizacion = $this->logica->actualizarPerfil($ci, $nombre, $apellido, $email, $codigoPostal, $archivoFoto);

            if ($resultadoActualizacion['exito']) {
                $mensaje_exito = $resultadoActualizacion['mensaje'];
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $usuario = $resultadoActualizacion['usuario'];
            } else {
                $mensaje_error = $resultadoActualizacion['mensaje'];
                $usuario = $this->logica->obtenerPorCI($ci);
            }
        } else {
            $usuario = $this->logica->obtenerPorCI($ci);
        }

        return [
            'usuario' => $usuario,
            'mensaje_exito' => $mensaje_exito,
            'mensaje_error' => $mensaje_error
        ];
    }
}
?>