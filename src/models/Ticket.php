<?php

class Ticket
{
    private $id;
    private $numeroTicket;
    private $fecha;
    private $empresa;
    private $items;
    private $subtotal;
    private $ivaTotal;
    private $total;
    private $metodoPago;
    private $cliente;

    public function __construct($empresa)
    {
        $this->empresa = $empresa;
        $this->fecha = new DateTime();
        $this->items = [];
        $this->subtotal = 0;
        $this->ivaTotal = 0;
        $this->total = 0;
        $this->generarNumeroTicket();
    }

    private function generarNumeroTicket()
    {
        // Formato: YYYYMMDD-XXXX donde XXXX es un número aleatorio
        $this->numeroTicket = date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function agregarItem($producto, $cantidad)
    {
        $item = new TicketItem($producto, $cantidad);
        $this->items[] = $item;
        $this->recalcularTotales();
    }

    private function recalcularTotales()
    {
        $this->subtotal = 0;
        $this->ivaTotal = 0;
        $this->total = 0;

        foreach ($this->items as $item) {
            $this->subtotal += $item->getSubtotal();
            $this->ivaTotal += $item->getIvaImporte();
            $this->total += $item->getTotal();
        }
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getNumeroTicket()
    {
        return $this->numeroTicket;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getEmpresa()
    {
        return $this->empresa;
    }
    public function getItems()
    {
        return $this->items;
    }
    public function getSubtotal()
    {
        return $this->subtotal;
    }
    public function getIvaTotal()
    {
        return $this->ivaTotal;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function getMetodoPago()
    {
        return $this->metodoPago;
    }
    public function getCliente()
    {
        return $this->cliente;
    }

    // Setters
    public function setMetodoPago($metodoPago)
    {
        $this->metodoPago = $metodoPago;
    }
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'numero_ticket' => $this->numeroTicket,
            // Fecha en formato ISO 8601
            'fecha' => $this->fecha->format(DateTime::ATOM),
            'empresa' => $this->empresa->toArray(),
            'items' => array_map(function ($item) {
                return $item->toArray();
            }, $this->items),
            'subtotal' => $this->subtotal,
            'iva_total' => $this->ivaTotal,
            'total' => $this->total,
            'metodo_pago' => $this->metodoPago,
            'cliente' => $this->cliente ? $this->cliente->toArray() : null
        ];
    }

    public function generarTicketImpresion()
    {
        $ticket = "";
        $ticket .= str_repeat("=", 40) . "\n";
        $ticket .= str_pad($this->empresa->getNombre(), 40, " ", STR_PAD_BOTH) . "\n";
        $ticket .= str_pad($this->empresa->getCif(), 40, " ", STR_PAD_BOTH) . "\n";
        $ticket .= str_pad($this->empresa->getDireccion(), 40, " ", STR_PAD_BOTH) . "\n";
        $ticket .= str_repeat("=", 40) . "\n";
        $ticket .= "Ticket #: " . $this->numeroTicket . "\n";
        $ticket .= "Fecha: " . $this->fecha->format('d/m/Y H:i:s') . "\n";
        $ticket .= str_repeat("-", 40) . "\n";
        $ticket .= sprintf("%-20s %3s %7s %7s\n", "Producto", "Cnt", "Precio", "Total");
        $ticket .= str_repeat("-", 40) . "\n";

        foreach ($this->items as $item) {
            $ticket .= sprintf(
                "%-20s %3d %7.2f %7.2f\n",
                substr($item->getProducto()->getNombre(), 0, 20),
                $item->getCantidad(),
                $item->getProducto()->getPrecio(),
                $item->getSubtotal()
            );
        }

        $ticket .= str_repeat("-", 40) . "\n";
        $ticket .= sprintf("%30s %7.2f\n", "Subtotal:", $this->subtotal);
        $ticket .= sprintf("%30s %7.2f\n", "IVA:", $this->ivaTotal);
        $ticket .= sprintf("%30s %7.2f\n", "Total:", $this->total);
        $ticket .= str_repeat("=", 40) . "\n";

        if ($this->metodoPago) {
            $ticket .= "Método de pago: " . $this->metodoPago . "\n";
        }

        $ticket .= "\n¡Gracias por su compra!\n";
        return $ticket;
    }
}
