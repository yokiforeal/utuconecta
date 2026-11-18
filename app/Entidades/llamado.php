<?php
class Llamado {
    private $idLlamado;
    private $idEmpresa;
    private $titulo;
    private $desarrollo;
    private $fecha;
    private $horario;
    private $vacantes;
    private $imagen;

    public function __construct($idLlamado, $idEmpresa, $titulo, $desarrollo, $fecha, $horario, $vacantes, $imagen) {
        $this->idLlamado = $idLlamado;
        $this->idEmpresa = $idEmpresa;
        $this->titulo = $titulo;
        $this->desarrollo = $desarrollo;
        $this->fecha = $fecha;
        $this->horario = $horario;
        $this->vacantes = $vacantes;
        $this->imagen = $imagen;
    }

    public function getIdLlamado() { return $this->idLlamado; }
    public function getIdEmpresa() { return $this->idEmpresa; }
    public function getTitulo() { return $this->titulo; }
    public function getDesarrollo() { return $this->desarrollo; }
    public function getFecha() { return $this->fecha; }
    public function getHorario() { return $this->horario; }
    public function getVacantes() { return $this->vacantes; }
    public function getImagen() { return $this->imagen; }
}
?>