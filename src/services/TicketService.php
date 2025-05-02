<?php

class TicketService extends BaseService
{
    private $clienteRepository;
    private $empresaRepository;

    public function __construct()
    {
        parent::__construct();
        $this->clienteRepository = new ClienteRepository();
        $this->empresaRepository = new EmpresaRepository();
    }

    protected function initializeRepository()
    {
        $this->repository = new TicketRepository();
    }

    public function buscarPorId($id)
    {
        try {
            $ticket = $this->repository->buscarPorId($id);
            if (!$ticket) {
                throw new Exception("Ticket no encontrado");
            }
            return $ticket;
        } catch (Exception $e) {
            error_log("Error en buscarPorId: " . $e->getMessage());
            throw $e;
        }
    }

    protected function createEntity(array $data)
    {
        $empresa = $this->empresaRepository->buscarPorId($data['empresa_id']);
        if (!$empresa) {
            throw new Exception("Empresa no encontrada");
        }

        $ticket = new Ticket($empresa);

        if (!empty($data['cliente_id'])) {
            $cliente = $this->clienteRepository->buscarPorId($data['cliente_id']);
            if ($cliente) {
                $ticket->setCliente($cliente);
            }
        }

        if (!empty($data['metodo_pago'])) {
            $ticket->setMetodoPago($data['metodo_pago']);
        }

        return $ticket;
    }

    protected function updateEntity($ticket, array $data)
    {
        if (!empty($data['cliente_id'])) {
            $cliente = $this->clienteRepository->buscarPorId($data['cliente_id']);
            if ($cliente) {
                $ticket->setCliente($cliente);
            }
        }

        if (!empty($data['metodo_pago'])) {
            $ticket->setMetodoPago($data['metodo_pago']);
        }

        return $ticket;
    }

    public function crearTicket($empresa)
    {
        return new Ticket($empresa);
    }

    public function agregarProducto($ticket, $producto, $cantidad)
    {
        $ticket->agregarItem($producto, $cantidad);
        return $ticket;
    }

    public function finalizarTicket($ticket, $metodoPago, $clienteId = null, $dniCliente = null)
    {
        // Asignar cliente por ID, o bien por DNI/CIF si se proporciona
        if ($clienteId) {
            $cliente = $this->clienteRepository->buscarPorId($clienteId);
            if (!$cliente) {
                throw new Exception("Cliente no encontrado");
            }
            $ticket->setCliente($cliente);
        } elseif ($dniCliente) {
            $cliente = $this->clienteRepository->buscarPorDniCif($dniCliente);
            if (!$cliente) {
                throw new Exception("Cliente no encontrado");
            }
            $ticket->setCliente($cliente);
        }

        $ticket->setMetodoPago($metodoPago);
        $this->repository->guardar($ticket);

        return $ticket;
    }

    public function generarImpresion($ticket)
    {
        return $ticket->generarTicketImpresion();
    }

    public function buscarPorNumero($numeroTicket)
    {
        return $this->repository->buscarPorNumeroTicket($numeroTicket);
    }

    public function buscarTickets($filtros)
    {
        return $this->repository->buscarPorFiltros($filtros);
    }

    public function buscarTicketsSinFactura()
    {
        return $this->repository->buscarTicketsSinFactura();
    }
}
