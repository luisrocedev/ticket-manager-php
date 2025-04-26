<?php

class FacturaController extends BaseCrudController
{
    private $ticketService;

    public function __construct()
    {
        parent::__construct();
        $this->ticketService = new TicketService();
    }

    protected function initializeService()
    {
        $this->service = new FacturaService();
        $this->entityName = 'Factura';
    }

    protected function validateData(array $data): array
    {
        $errors = [];

        if (empty($data['ticket_id'])) {
            $errors[] = 'El ticket es requerido';
        }

        if (!empty($errors)) {
            throw new Exception(implode('. ', $errors));
        }

        return $data;
    }

    protected function prepareDataForResponse($factura): array
    {
        return [
            'id' => $factura->getId(),
            'numero_factura' => $factura->getNumeroFactura(),
            'fecha_emision' => $factura->getFechaEmision()->format('Y-m-d'),
            'ticket' => $factura->getTicket()->toArray(),
            'estado' => $factura->getEstado()
        ];
    }

    public function index()
    {
        // Filtros
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-t');
        $estado = $_GET['estado'] ?? null;

        try {
            $facturas = $this->service->buscarFacturas([
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => $estado
            ]);

            return $this->jsonResponse([
                'success' => true,
                'data' => array_map([$this, 'prepareDataForResponse'], $facturas)
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function generarFactura($ticketId)
    {
        try {
            $factura = $this->service->generarFactura($ticketId);
            return $this->jsonResponse([
                'success' => true,
                'data' => $this->prepareDataForResponse($factura),
                'message' => 'Factura generada correctamente'
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function descargarPDF($id)
    {
        try {
            $factura = $this->service->buscarPorId($id);
            if (!$factura) {
                throw new Exception('Factura no encontrada');
            }

            $pdfContent = $this->service->generarPDF($factura);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="factura_' . $factura->getNumeroFactura() . '.pdf"');
            echo $pdfContent;
            exit;
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
