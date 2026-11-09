<?php
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../Entidades/Usuario.php';

class UsuarioPersistencia {

    public function buscarPorCI($ci) {
        $conn = obtenerConexion();
        $sql = "SELECT CI, Nombre, Apellido, Mail, Codigo_Postal, Foto_perfil, Password FROM usuarios WHERE CI = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $stmt->close();
            $conn->close();
            return new Usuario(
                $row['CI'],
                $row['Nombre'],
                $row['Apellido'],
                $row['Mail'],
                $row['Codigo_Postal'],
                $row['Foto_perfil'],
                $row['Password']
            );
        }

        $stmt->close();
        $conn->close();
        return null;
    }

    public function esPostulante($ci) {
        $conn = obtenerConexion();
        $sql = "SELECT CI FROM postulantes WHERE CI = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $res = $stmt->get_result();
        $existe = ($res->num_rows > 0);
        $stmt->close();
        $conn->close();
        return $existe;
    }

    public function esEmpresa($ci) {
        $conn = obtenerConexion();
        $sql = "SELECT ID_empresa, Nombre_empresa FROM empresas WHERE CI = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $res = $stmt->get_result();
        $empresaData = $res->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $empresaData ? $empresaData : false;
    }

    public function guardar(Usuario $usuario, $esEmpresa = false, $nombreEmpresa = "", $localidad = "") {
        $conn = obtenerConexion();
        
        // Iniciamos transacción para asegurar consistencia entre usuarios y postulantes/empresas
        $conn->begin_transaction();

        try {
            $sqlUsuario = "INSERT INTO usuarios (CI, Nombre, Apellido, Mail, Codigo_Postal, Foto_perfil, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sqlUsuario);
            
            $ci = $usuario->getCi();
            $nombre = $usuario->getNombre();
            $apellido = $usuario->getApellido();
            $mail = $usuario->getMail();
            $cp = $usuario->getCodigoPostal();
            $foto = $usuario->getFotoPerfil();
            $pass = $usuario->getPassword();

            $stmt1->bind_param("isssiss", $ci, $nombre, $apellido, $mail, $cp, $foto, $pass); 
            $stmt1->execute();
            $stmt1->close();

            if ($esEmpresa) {
                $sqlEmpresa = "INSERT INTO empresas (CI, Nombre_empresa, Localidad) VALUES (?, ?, ?)";
                $stmt2 = $conn->prepare($sqlEmpresa);
                $stmt2->bind_param("iss", $ci, $nombreEmpresa, $localidad);
                $stmt2->execute();
                $stmt2->close();
            } else {
                $sqlPostulante = "INSERT INTO postulantes (CI, Localidad) VALUES (?, ?)";
                $stmt2 = $conn->prepare($sqlPostulante);
                $stmt2->bind_param("is", $ci, $localidad);
                $stmt2->execute();
                $stmt2->close();
            }

            $conn->commit();
            $conn->close();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            $conn->close();
            return false;
        }
    }

    public function actualizar(Usuario $usuario) {
        $conn = obtenerConexion();
        $sql = "UPDATE usuarios SET Nombre = ?, Apellido = ?, Mail = ?, Codigo_Postal = ?, Foto_perfil = ? WHERE CI = ?";
        $stmt = $conn->prepare($sql);

        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $mail = $usuario->getMail();
        $cp = $usuario->getCodigoPostal();
        $foto = $usuario->getFotoPerfil();
        $ci = $usuario->getCi();

        $stmt->bind_param("sssisi", $nombre, $apellido, $mail, $cp, $foto, $ci);
        $exito = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $exito;
    }
}
?>