<?php
class Postulacion {
    private $ci;
    private $idLlamado;
    private $fecha;
    private $nota;
    private $archivo;
    private $expectativaSalarial;

    public function __construct($ci, $idLlamado, $fecha, $nota, $archivo, $expectativaSalarial) {
        $this->ci = $ci;
        $this->idLlamado = $idLlamado;
        $this->fecha = $fecha;
        $this->nota = $nota;
        $this->archivo = $archivo;
        $this->expectativaSalarial = $expectativaSalarial;
    }

    public function getCi() { return $this->ci; }
    public function getIdLlamado() { return $this->idLlamado; }
    public function getFecha() { return $this->fecha; }
    public function getNota() { return $this->nota; }
    public function getArchivo() { return $this->archivo; }
    public function getExpectativaSalarial() { return $this->expectativaSalarial; }
}
?>