<?php

class Factura
{
    private $id;
    private $numeroFactura;
    private $ticket;
    private $fechaEmision;
    private $estado;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
        $this->fechaEmision = new DateTime();
        $this->estado = 'emitida';
        $this->generarNumeroFactura();
    }

    private function generarNumeroFactura()
    {
        // Formato: F-YYYY-XXXXX donde XXXXX es secuencial
        $this->numeroFactura = 'F-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }
    public function getTicket()
    {
        return $this->ticket;
    }
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }
    public function getEstado()
    {
        return $this->estado;
    }

    // Setters
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'numeroFactura' => $this->numeroFactura,
            'ticket' => $this->ticket->toArray(),
            'fechaEmision' => $this->fechaEmision->format('Y-m-d H:i:s'),
            'estado' => $this->estado
        ];
    }
}
