<?php
require_once __DIR__ . '/../Persistencia/usuarioPersistencia.php';

class UsuarioLogica {
    private $persistencia;

    public function __construct() {
        $this->persistencia = new UsuarioPersistencia();
    }

    public function obtenerPorCI($ci) {
        return $this->persistencia->buscarPorCI($ci);
    }

    public function login($ci, $password) {
        if (empty($ci) || empty($password)) {
            return ["exito" => false, "mensaje" => "Todos los campos son obligatorios."];
        }

        $usuario = $this->persistencia->buscarPorCI($ci);

        if (!$usuario) {
            return ["exito" => false, "mensaje" => "Usuario o contraseña incorrectos."];
        }

        if ($password === $usuario->getPassword() || password_verify($password, $usuario->getPassword())) {
            return ["exito" => true, "usuario" => $usuario];
        } else {
            return ["exito" => false, "mensaje" => "Usuario o contraseña incorrectos."];
        }
    }

    public function registrarUsuario($ci, $nombre, $apellido, $mail, $codigoPostal, $password) {
        if (empty($ci) || empty($nombre) || empty($apellido) || empty($password)) {
            return ["exito" => false, "mensaje" => "Completa todos los campos obligatorios."];
        }

        if ($this->persistencia->buscarPorCI($ci) !== null) {
            return ["exito" => false, "mensaje" => "La cédula ya está registrada."];
        }

        $passHash = password_hash($password, PASSWORD_DEFAULT);
        $nuevoUsuario = new Usuario($ci, $nombre, $apellido, $mail, $codigoPostal, null, $passHash);

        if ($this->persistencia->guardar($nuevoUsuario)) {
            return ["exito" => true, "mensaje" => "Registro completado con éxito."];
        } else {
            return ["exito" => false, "mensaje" => "Error al guardar en la base de datos."];
        }
    }

    public function actualizarPerfil($ci, $nombre, $apellido, $mail, $codigoPostal, $archivoFoto) {
        if (empty($ci) || empty($nombre) || empty($apellido)) {
            return ["exito" => false, "mensaje" => "El nombre y apellido no pueden estar vacíos."];
        }

        $usuarioActual = $this->persistencia->buscarPorCI($ci);
        if (!$usuarioActual) {
            return ["exito" => false, "mensaje" => "El usuario no existe."];
        }

        $rutaFoto = $usuarioActual->getFotoPerfil();

        if (isset($archivoFoto) && $archivoFoto['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($archivoFoto['name'], PATHINFO_EXTENSION));
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $extensionesPermitidas)) {
                return ["exito" => false, "mensaje" => "Formato de imagen no permitido (solo JPG, PNG, WEBP)."];
            }

            $nombreArchivo = $ci . '_' . time() . '.' . $ext;
            $carpetaDestino = __DIR__ . '/../../static/img/perfiles/';

            if (!file_exists($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $rutaServidor = $carpetaDestino . $nombreArchivo;
            $rutaRelativaDB = 'static/img/perfiles/' . $nombreArchivo;

            if (move_uploaded_file($archivoFoto['tmp_name'], $rutaServidor)) {
                $rutaFoto = $rutaRelativaDB;
            } else {
                return ["exito" => false, "mensaje" => "Error al guardar la imagen en el servidor."];
            }
        }

        $usuarioModificado = new Usuario($ci, $nombre, $apellido, $mail, $codigoPostal, $rutaFoto, $usuarioActual->getPassword());

        if ($this->persistencia->actualizar($usuarioModificado)) {
            return ["exito" => true, "mensaje" => "Perfil actualizado correctamente.", "usuario" => $usuarioModificado];
        } else {
            return ["exito" => false, "mensaje" => "Error al actualizar los datos en la base de datos."];
        }
    }
}
?>