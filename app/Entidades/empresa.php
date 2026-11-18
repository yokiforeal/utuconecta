<?php
class Empresa {
    private $idEmpresa;
    private $ci;
    private $nombreEmpresa;
    private $localidad;

    public function __construct($idEmpresa, $ci, $nombreEmpresa, $localidad) {
        $this->idEmpresa = $idEmpresa;
        $this->ci = $ci;
        $this->nombreEmpresa = $nombreEmpresa;
        $this->localidad = $localidad;
    }

    public function getIdEmpresa() { return $this->idEmpresa; }
    public function getCi() { return $this->ci; }
    public function getNombreEmpresa() { return $this->nombreEmpresa; }
    public function getLocalidad() { return $this->localidad; }
}
?>