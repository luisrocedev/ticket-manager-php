<?php

class Producto {
    private $id;
    private $codigo;
    private $nombre;
    private $descripcion;
    private $precio;
    private $iva;
    private $stock;

    public function __construct($codigo, $nombre, $precio, $iva = 21, $descripcion = '', $stock = 0) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->iva = $iva;
        $this->stock = $stock;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getCodigo() { return $this->codigo; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getIva() { return $this->iva; }
    public function getStock() { return $this->stock; }

    // Setters
    public function setCodigo($codigo) { $this->codigo = $codigo; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setIva($iva) { $this->iva = $iva; }
    public function setStock($stock) { $this->stock = $stock; }

    public function calcularPrecioConIva() {
        return $this->precio * (1 + ($this->iva / 100));
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'iva' => $this->iva,
            'stock' => $this->stock
        ];
    }
}