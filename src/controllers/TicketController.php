<?php

class TicketController extends BaseCrudController
{
    private $ticketService;
    private $empresaRepository;

    public function __construct()
    {
        $this->ticketService = new TicketService();
        $this->empresaRepository = new EmpresaRepository();
        parent::__construct();
    }

    protected function initializeService()
    {
        $this->service = $this->ticketService;
        $this->entityName = 'Ticket';
    }

    public function nuevo()
    {
        try {
            $empresa = $this->empresaRepository->buscarPorId(1);
            if (!$empresa) {
                throw new Exception("No se encontró la empresa configurada");
            }

            $ticket = $this->ticketService->crearTicket($empresa);
            return [
                'ticket' => $ticket,
                'empresa' => $empresa
            ];
        } catch (Exception $e) {
            throw new Exception("Error al crear nuevo ticket: " . $e->getMessage());
        }
    }

    protected function validateData(array $data): array
    {
        $errors = [];

        if (empty($data['empresa_id'])) {
            $errors[] = 'La empresa es requerida';
        }

        if (!empty($errors)) {
            throw new Exception(implode('. ', $errors));
        }

        return $data;
    }

    protected function prepareDataForResponse($ticket): array
    {
        return $ticket->toArray();
    }

    public function verDetalle($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                throw new Exception("ID de ticket inválido");
            }

            $ticket = $this->ticketService->buscarPorId($id);
            if (!$ticket) {
                throw new Exception("Ticket no encontrado");
            }

            $data = $this->prepareDataForResponse($ticket);

            if (!$data) {
                throw new Exception("Error al preparar los datos del ticket");
            }

            return $this->jsonResponse([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            error_log("Error en verDetalle: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function reimprimirTicket($id)
    {
        try {
            $ticket = $this->ticketService->buscarPorId($id);
            if (!$ticket) {
                throw new Exception("Ticket no encontrado");
            }

            $impresion = $this->ticketService->generarImpresion($ticket);
            return $this->jsonResponse([
                'success' => true,
                'data' => $impresion,
                'message' => 'Ticket generado correctamente'
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function generarFactura($id)
    {
        try {
            $ticket = $this->ticketService->buscarPorId($id);
            if (!$ticket) {
                throw new Exception("Ticket no encontrado");
            }

            if (!$ticket->getCliente()) {
                throw new Exception("El ticket debe tener un cliente asignado para generar factura");
            }

            $facturaService = new FacturaService();
            $factura = $facturaService->generarFactura($ticket->getId());

            return $this->jsonResponse([
                'success' => true,
                'data' => [
                    'factura_id' => $factura->getId(),
                    'numero_factura' => $factura->getNumeroFactura()
                ],
                'message' => 'Factura generada correctamente'
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // API endpoints
    public function agregarProducto()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ticket = $this->ticketService->buscarPorNumero($data['ticketId']);

            if (!$ticket) {
                throw new Exception("Ticket no encontrado");
            }

            $producto = (new ProductoRepository())->buscarPorId($data['productoId']);
            if (!$producto) {
                throw new Exception("Producto no encontrado");
            }

            $ticket = $this->ticketService->agregarProducto($ticket, $producto, $data['cantidad']);

            return $this->jsonResponse([
                'success' => true,
                'ticket' => $ticket->toArray()
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function finalizarTicket()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ticket = $this->ticketService->buscarPorNumero($data['ticketId']);

            if (!$ticket) {
                throw new Exception("Ticket no encontrado");
            }

            $ticket = $this->ticketService->finalizarTicket($ticket, $data['metodoPago'], $data['dniCliente']);
            $impresion = $this->ticketService->generarImpresion($ticket);

            return $this->jsonResponse([
                'success' => true,
                'ticket' => $ticket->toArray(),
                'impresion' => $impresion
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function lista()
    {
        try {
            $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
            $numeroTicket = isset($_GET['numeroTicket']) ? $_GET['numeroTicket'] : null;
            $metodoPago = isset($_GET['metodoPago']) ? $_GET['metodoPago'] : null;

            $filtros = [
                'fecha' => $fecha,
                'numeroTicket' => $numeroTicket,
                'metodoPago' => $metodoPago
            ];

            $tickets = $this->ticketService->buscarTickets($filtros);

            return [
                'tickets' => $tickets,
                'filtros' => $filtros
            ];
        } catch (Exception $e) {
            throw new Exception("Error al obtener lista de tickets: " . $e->getMessage());
        }
    }

    public function getTicketsDisponibles()
    {
        try {
            $tickets = $this->ticketService->buscarTicketsSinFactura();
            return $this->jsonResponse([
                'success' => true,
                'data' => array_map(function ($ticket) {
                    return $ticket->toArray();
                }, $tickets)
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
