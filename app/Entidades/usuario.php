<?php
class Usuario {
    // atributillos 
    private $ci;
    private $nombre;
    private $apellido;
    private $mail;
    private $codigoPostal;
    private $fotoPerfil;
    private $password;

    // bob... (el constructor)
    public function __construct($ci = null, $nombre = null, $apellido = null, $mail = null, $codigoPostal = null, $fotoPerfil = null, $password = null) {
        $this->ci = $ci;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->mail = $mail;
        $this->codigoPostal = $codigoPostal;
        $this->fotoPerfil = $fotoPerfil;
        $this->password = $password;
    }

    // pinches getters xddd
    public function getCi() { return $this->ci; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    public function getMail() { return $this->mail; }
    public function getCodigoPostal() { return $this->codigoPostal; }
    public function getFotoPerfil() { return $this->fotoPerfil; }
    public function getPassword() { return $this->password; }
    
    // pinches setter xdd
    public function setCi($ci) { $this->ci = $ci; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setMail($mail) { $this->mail = $mail; }
    public function setCodigoPostal($codigoPostal) { $this->codigoPostal = $codigoPostal; }
    public function setFotoPerfil($fotoPerfil) { $this->fotoPerfil = $fotoPerfil; }
    public function setPassword($password) { $this->password = $password; }
}
?>