<?php
class Empresa {
    //atributos
    private $idEmpresa;
    private $ci;
    private $nombreEmpresa;
    private $fotoEmpresa;
    private $localidad;

    //constructor
    public function __construct($idEmpresa = null, $ci = null, $nombreEmpresa = null, $fotoEmpresa = null, $localidad = null) {
        $this->idEmpresa = $idEmpresa;
        $this->ci = $ci;
        $this->nombreEmpresa = $nombreEmpresa;
        $this->fotoEmpresa = $fotoEmpresa;
        $this->localidad = $localidad;
    }

    //getters
    public function getIdEmpresa() { return $this->idEmpresa; }
    public function getCi() { return $this->ci; }
    public function getNombreEmpresa() { return $this->nombreEmpresa; }
    public function getFotoEmpresa() { return $this->fotoEmpresa; }
    public function getLocalidad() { return $this->localidad; }

    //setters
    public function setIdEmpresa($idEmpresa) { $this->idEmpresa = $idEmpresa; }
    public function setCi($ci) { $this->ci = $ci; }
    public function setNombreEmpresa($nombreEmpresa) { $this->nombreEmpresa = $nombreEmpresa; }
    public function setFotoEmpresa($fotoEmpresa) { $this->fotoEmpresa = $fotoEmpresa; }
    public function setLocalidad($localidad) { $this->localidad = $localidad; }
}
?>