<?php
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../Entidades/llamado.php';

class LlamadoPersistencia {
    public function obtenerTodos() {
        $conn = obtenerConexion();
        $sql = "SELECT ID_llamado, ID_empresa, Horario, Imagen, Desarrollo, Fecha, Titulo, Vacantes FROM llamados ORDER BY ID_llamado DESC";
        $resultado = $conn->query($sql);
        $llamados = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $llamados[] = new Llamado(
                    $row['ID_llamado'],
                    $row['ID_empresa'],
                    $row['Titulo'],
                    $row['Desarrollo'],
                    $row['Fecha'],
                    $row['Horario'],
                    $row['Vacantes'],
                    $row['Imagen']
                );
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
            $llamados[] = new Llamado(
                $row['ID_llamado'],
                $row['ID_empresa'],
                $row['Titulo'],
                $row['Desarrollo'],
                $row['Fecha'],
                $row['Horario'],
                $row['Vacantes'],
                $row['Imagen']
            );
        }
        $stmt->close();
        $conn->close();
        return $llamados;
    }

    public function buscarLlamados($termino) {
        $conn = obtenerConexion();
        $busqueda = "%" . $termino . "%";
        $sql = "SELECT ID_llamado, ID_empresa, Horario, Imagen, Desarrollo, Fecha, Titulo, Vacantes 
                FROM llamados 
                WHERE Titulo LIKE ? OR Desarrollo LIKE ? 
                ORDER BY ID_llamado DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $busqueda, $busqueda);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $llamados = [];
        while ($row = $resultado->fetch_assoc()) {
            $llamados[] = new Llamado(
                $row['ID_llamado'],
                $row['ID_empresa'],
                $row['Titulo'],
                $row['Desarrollo'],
                $row['Fecha'],
                $row['Horario'],
                $row['Vacantes'],
                $row['Imagen']
            );
        }
        
        $stmt->close();
        $conn->close();
        return $llamados;
    }

    public function crearLlamado($ciEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $imagen) {
        $conn = obtenerConexion();
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

        $sql = "INSERT INTO llamados (ID_empresa, Titulo, Desarrollo, Fecha, Horario, Vacantes, Imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssis", $idEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $imagen);
        $exito = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $exito;
    }

    public function actualizarLlamado($idLlamado, $titulo, $desarrollo, $vacantes, $horario, $imagen) {
        $conn = obtenerConexion(); 
        $sql = "UPDATE llamados SET Titulo = ?, Desarrollo = ?, Vacantes = ?, Horario = ?, Imagen = ? WHERE ID_llamado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $titulo, $desarrollo, $vacantes, $horario, $imagen, $idLlamado);
        $resultado = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $resultado;
    }

    public function eliminarLlamado($idLlamado) {
        $conn = obtenerConexion();
        $sqlPost = "DELETE FROM postula WHERE ID_llamado = ?";
        $stmtPost = $conn->prepare($sqlPost);
        $stmtPost->bind_param("i", $idLlamado);
        $stmtPost->execute();
        $stmtPost->close();

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