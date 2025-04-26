<?php

class Cliente {
    private $id;
    private $nombre;
    private $apellidos;
    private $dniCif;
    private $direccion;
    private $telefono;
    private $email;

    public function __construct($nombre, $apellidos, $dniCif, $direccion = null, $telefono = null, $email = null) {
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->dniCif = $dniCif;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getDniCif() { return $this->dniCif; }
    public function getDireccion() { return $this->direccion; }
    public function getTelefono() { return $this->telefono; }
    public function getEmail() { return $this->email; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellidos($apellidos) { $this->apellidos = $apellidos; }
    public function setDniCif($dniCif) { $this->dniCif = $dniCif; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setEmail($email) { $this->email = $email; }

    public function getNombreCompleto() {
        return trim($this->nombre . ' ' . $this->apellidos);
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'dniCif' => $this->dniCif,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email
        ];
    }
}