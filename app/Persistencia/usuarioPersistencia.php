<?php
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../Entidades/Usuario.php';

class UsuarioPersistencia {

    // buscar un usuario por su ci
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

    // insertar un nuevo usuario
    public function guardar(Usuario $usuario) {
        $conn = obtenerConexion();
        $sql = "INSERT INTO usuarios (CI, Nombre, Apellido, Mail, Codigo_Postal, Foto_perfil, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $ci = $usuario->getCi();
        $nombre = $usuario->getNombre();
        $apellido = $usuario->getApellido();
        $mail = $usuario->getMail();
        $cp = $usuario->getCodigoPostal();
        $foto = $usuario->getFotoPerfil();
        $pass = $usuario->getPassword();

        $stmt->bind_param("isssiss", $ci, $nombre, $apellido, $mail, $cp, $foto, $pass); 
        $exito = $stmt->execute();

        $stmt->close();
        $conn->close();
        return $exito;
    }

    // actualizar los datos del usuario
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