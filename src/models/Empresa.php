<?php

class Empresa {
    private $id;
    private $nombre;
    private $cif;
    private $direccion;
    private $telefono;
    private $email;
    private $logo;

    public function __construct($nombre, $cif, $direccion, $telefono, $email, $logo = null) {
        $this->nombre = $nombre;
        $this->cif = $cif;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->logo = $logo;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCif() { return $this->cif; }
    public function getDireccion() { return $this->direccion; }
    public function getTelefono() { return $this->telefono; }
    public function getEmail() { return $this->email; }
    public function getLogo() { return $this->logo; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCif($cif) { $this->cif = $cif; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setEmail($email) { $this->email = $email; }
    public function setLogo($logo) { $this->logo = $logo; }

    public function toArray() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'cif' => $this->cif,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'logo' => $this->logo
        ];
    }
}