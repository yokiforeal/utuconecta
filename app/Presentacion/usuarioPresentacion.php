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
            $cedula       = trim($_POST['cedula'] ?? '');
            $password     = trim($_POST['password'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $localidad    = trim($_POST['localidad'] ?? '');
            $codigoPostal = trim($_POST['codigo_postal'] ?? '');
            
            // Opcional: si tu formulario distingue si es empresa o postulante, puedes ajustarlo aquí o registrar por defecto como postulante
            $esEmpresa    = isset($_POST['es_empresa']) ? true : false;
            $nombreEmp    = $_POST['nombre_empresa'] ?? '';

            $res = $this->logica->registrarUsuario($cedula, $nombre, $apellido, $email, $codigoPostal, $password, $localidad, $esEmpresa, $nombreEmp);

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