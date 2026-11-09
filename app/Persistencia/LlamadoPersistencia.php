<?php
require_once __DIR__ . '/../../conexion.php';

class LlamadoPersistencia {
    public function obtenerTodos() {
        $conn = obtenerConexion();
        $sql = "SELECT ID_llamado, ID_empresa, Horario, Imagen, Desarrollo, Fecha, Titulo, Vacantes FROM llamados ORDER BY ID_llamado DESC";
        $resultado = $conn->query($sql);
        $llamados = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $llamados[] = $row;
            }
        }
        $conn->close();
        return $llamados;
    }

    public function obtenerPorEmpresaCI($ci) {
        $conn = obtenerConexion();
        $sql = "SELECT l.ID_llamado, l.ID_empresa, l.Horario, l.Imagen, l.Desarrollo, l.Fecha, l.Titulo, l.Vacantes 
                FROM llamados l 
                JOIN empresas e ON l.ID_empresa = e.ID_empresa 
                WHERE e.CI = ? ORDER BY l.ID_llamado DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ci);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $llamados = [];
        while ($row = $resultado->fetch_assoc()) {
            $llamados[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $llamados;
    }

    public function crearLlamado($ciEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $imagen) {
        $conn = obtenerConexion();
        
        // Buscar el ID_empresa a partir del CI del usuario conectado
        $sqlEmpresa = "SELECT ID_empresa FROM empresas WHERE CI = ?";
        $stmt = $conn->prepare($sqlEmpresa);
        $stmt->bind_param("i", $ciEmpresa);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$row = $res->fetch_assoc()) {
            $stmt->close();
            $conn->close();
            return false;
        }
        $idEmpresa = $row['ID_empresa'];
        $stmt->close();

        // Insertar el llamado
        $sql = "INSERT INTO llamados (ID_empresa, Titulo, Desarrollo, Fecha, Horario, Vacantes, Imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssis", $idEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $imagen);
        $exito = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $exito;
    }

    public function actualizarLlamado($idLlamado, $titulo, $desarrollo, $vacantes, $horario, $imagen) {
    $conn = obtenerConexion(); // O tu forma de conectar a la base de datos
    
    $sql = "UPDATE llamados 
            SET Titulo = ?, Desarrollo = ?, Vacantes = ?, Horario = ?, Imagen = ? 
            WHERE ID_llamado = ?";
            
    $stmt = $conn->prepare($sql);
    
    // Parámetros: 4 strings (s), 1 entero (i), 1 string (s), 1 entero (i)
    // Orden exacto de los '?': Titulo, Desarrollo, Vacantes, Horario, Imagen, ID_llamado
    $stmt->bind_param("ssissi", $titulo, $desarrollo, $vacantes, $horario, $imagen, $idLlamado);
    
    $resultado = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $resultado;
    }

    public function eliminarLlamado($idLlamado) {
        $conn = obtenerConexion();
        // Borramos primero las postulaciones asociadas para mantener la integridad referencial
        $sqlPost = "DELETE FROM postula WHERE ID_llamado = ?";
        $stmtPost = $conn->prepare($sqlPost);
        $stmtPost->bind_param("i", $idLlamado);
        $stmtPost->execute();
        $stmtPost->close();

        // Luego eliminamos el llamado
        $sql = "DELETE FROM llamados WHERE ID_llamado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idLlamado);
        $exito = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $exito;
    }
}
?>