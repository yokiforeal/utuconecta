<?php
require_once __DIR__ . '/../Entidades/Usuario.php';
require_once __DIR__ . '/../Entidades/Empresa.php';

class UsuarioPersistencia {
    private $conexion;

    public function __construct() {
        $archivoConexion = __DIR__ . '/../../conexion.php';
        if (file_exists($archivoConexion)) {
            require_once $archivoConexion;
        }

        if (isset($conn) && $conn instanceof mysqli) {
            $this->conexion = $conn;
        } else {
            $this->conexion = new mysqli("localhost", "root", "", "utuconecta");
            if ($this->conexion->connect_error) {
                die("Error de conexión a la base de datos: " . $this->conexion->connect_error);
            }
            $this->conexion->set_charset("utf8");
        }
    }

    public function buscarPorCI($ci) {
        $sql = "SELECT * FROM usuarios WHERE CI = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("s", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            return new Usuario(
                $row['CI'],
                $row['Nombre'],
                $row['Apellido'],
                $row['Mail'],
                $row['Codigo_Postal'] ?? $row['codigo_postal'] ?? '',
                $row['Foto_perfil'] ?? null,
                $row['Password']
            );
        }
        return null;
    }

    public function esEmpresa($ci) {
        $sql = "SELECT e.id_empresa, e.nombre_empresa FROM empresas e JOIN usuarios u ON e.CI = u.CI WHERE u.CI = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            return new Empresa($row['id_empresa'], $row['nombre_empresa'], $ci);
        }
        return false;
    }

    public function guardar($usuario, $esEmpresa = false, $nombreEmpresa = "", $localidad = "") {
        $ci = $usuario->getCi();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $mail = $usuario->getMail();
        $cp = $usuario->getCodigoPostal();
        $pass = $usuario->getPassword();

        // 1. Guardar en la tabla principal de usuarios
        $sql = "INSERT INTO usuarios (CI, Nombre, Apellido, Mail, Codigo_Postal, Password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ssssss", $ci, $nombre, $apellido, $mail, $cp, $pass);

        if ($stmt->execute()) {
            // 2. Como el registro es exclusivo de postulantes, lo agregamos siempre a la tabla 'postulantes'
            $sqlPostulante = "INSERT INTO postulantes (CI) VALUES (?)";
            $stmtPostulante = $this->conexion->prepare($sqlPostulante);
            if ($stmtPostulante) {
                $stmtPostulante->bind_param("s", $ci);
                $stmtPostulante->execute();
            }
            return true;
        }
        return false;
    }

    public function actualizar($usuario) {
        $sql = "UPDATE usuarios SET Nombre = ?, Apellido = ?, Mail = ?, Codigo_Postal = ?, Foto_perfil = ? WHERE CI = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $mail = $usuario->getMail();
        $cp = $usuario->getCodigoPostal();
        $foto = $usuario->getFotoPerfil();
        $ci = $usuario->getCi();

        $stmt->bind_param("ssssss", $nombre, $apellido, $mail, $cp, $foto, $ci);
        return $stmt->execute();
    }
}
?>