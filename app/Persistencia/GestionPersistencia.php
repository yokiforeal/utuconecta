<?php
require_once __DIR__ . '/../../conexion.php';

class GestionPersistencia {
    
    public function registrarGestion($ciAdmin, $idLlamado, $modificacion) {
        $conn = obtenerConexion();
        $fecha = date('Y-m-d');
        
        $sql = "INSERT INTO gestiona (CI, ID_llamado, Modificacion, Fecha) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $ciAdmin, $idLlamado, $modificacion, $fecha);
        $exito = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        return $exito;
    }
}
?>