<?php

class FacturaService extends BaseService
{
    private $ticketService;

    public function __construct()
    {
        parent::__construct();
        $this->ticketService = new TicketService();
    }

    protected function initializeRepository()
    {
        $this->repository = new FacturaRepository();
    }

    protected function createEntity(array $data)
    {
        $ticket = $this->ticketService->buscarPorId($data['ticket_id']);
        if (!$ticket) {
            throw new Exception("Ticket no encontrado");
        }
        return new Factura($ticket);
    }

    protected function updateEntity($factura, array $data)
    {
        if (isset($data['estado'])) {
            $factura->setEstado($data['estado']);
        }
        return $factura;
    }

    public function buscarFacturas(array $filtros)
    {
        return $this->repository->buscarPorFiltros($filtros);
    }

    public function generarFactura($ticketId)
    {
        $ticket = $this->ticketService->buscarPorId($ticketId);
        if (!$ticket) {
            throw new Exception("Ticket no encontrado");
        }

        if (!$ticket->getCliente()) {
            throw new Exception("El ticket debe tener un cliente asignado para generar una factura");
        }

        $factura = new Factura($ticket);
        if (!$this->repository->crear($factura)) {
            throw new Exception("Error al crear la factura");
        }

        return $this->repository->buscarPorId($this->repository->lastInsertId());
    }

    public function generarPDF(Factura $factura)
    {
        // Aquí iría la lógica para generar el PDF
        // Por ahora retornamos un mensaje simple
        return "Contenido del PDF para la factura " . $factura->getNumeroFactura();
    }
}
