<?php

class TicketItem {
    private $id;
    private $producto;
    private $cantidad;
    private $precioUnitario;
    private $subtotal;
    private $ivaImporte;
    private $total;

    public function __construct($producto, $cantidad) {
        $this->producto = $producto;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $producto->getPrecio();
        $this->calcularImportes();
    }

    private function calcularImportes() {
        $this->subtotal = $this->precioUnitario * $this->cantidad;
        $this->ivaImporte = $this->subtotal * ($this->producto->getIva() / 100);
        $this->total = $this->subtotal + $this->ivaImporte;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getProducto() { return $this->producto; }
    public function getCantidad() { return $this->cantidad; }
    public function getPrecioUnitario() { return $this->precioUnitario; }
    public function getSubtotal() { return $this->subtotal; }
    public function getIvaImporte() { return $this->ivaImporte; }
    public function getTotal() { return $this->total; }

    // Setters
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
        $this->calcularImportes();
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'producto' => $this->producto->toArray(),
            'cantidad' => $this->cantidad,
            'precioUnitario' => $this->precioUnitario,
            'subtotal' => $this->subtotal,
            'ivaImporte' => $this->ivaImporte,
            'total' => $this->total
        ];
    }
}