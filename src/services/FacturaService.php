<?php

use Dompdf\Dompdf;

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
        $createdFactura = $this->repository->crear($factura);
        if (!$createdFactura) {
            throw new Exception("Error al crear la factura");
        }

        return $createdFactura;
    }

    public function generarPDF(Factura $factura)
    {
        // Generar PDF usando Dompdf
        $data = $factura->toArray();
        // Renderizar HTML de la factura
        ob_start();
        include __DIR__ . '/../views/facturas/pdf.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}
