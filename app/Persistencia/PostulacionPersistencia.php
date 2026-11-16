<?php
require_once __DIR__ . '/../../conexion.php';

class PostulacionPersistencia { // <--- Asegúrate de que diga 'PostulacionPersistencia' con P y P mayúsculas
    
    public function yaSePostulo($ci, $idLlamado) {
        $conn = obtenerConexion();
        $sql = "SELECT * FROM postula WHERE CI = ? AND ID_llamado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $ci, $idLlamado);
        $stmt->execute();
        $res = $stmt->get_result();
        $existe = ($res->num_rows > 0);
        $stmt->close();
        $conn->close();
        return $existe;
    }

    public function registrarPostulacion($ci, $idLlamado, $nota, $expectativa, $archivo) {
        $conn = obtenerConexion();
        $fecha = date('Y-m-d');
        
        $sql = "INSERT INTO postula (CI, ID_llamado, Fecha, Nota, Archivos, Expectativa_salarial) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissis", $ci, $idLlamado, $fecha, $nota, $archivo, $expectativa);
        $exito = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        return $exito;
    }

    public function cancelarPostulacion($ci, $idLlamado) {
        $conn = obtenerConexion();
        $sql = "DELETE FROM postula WHERE CI = ? AND ID_llamado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $ci, $idLlamado);
        $exito = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        return $exito;
    }

    public function obtenerPostulantesPorLlamado($idLlamado) {
    $conn = obtenerConexion();
    $sql = "SELECT p.*, u.Nombre, u.Apellido, u.Mail AS Correo 
            FROM postula p 
            JOIN usuarios u ON p.CI = u.CI 
            WHERE p.ID_llamado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idLlamado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $postulantes = [];
    while ($row = $resultado->fetch_assoc()) {
        $postulantes[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $postulantes;
    }
}
?>