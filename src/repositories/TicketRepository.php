<?php

class TicketRepository
{
    private $db;
    private $clienteRepository;
    private $empresaRepository;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->clienteRepository = new ClienteRepository();
        $this->empresaRepository = new EmpresaRepository();
    }

    public function guardar(Ticket $ticket)
    {
        try {
            $this->db->beginTransaction();

            // Insertar el ticket
            $sql = "INSERT INTO tickets (numero_ticket, fecha, empresa_id, cliente_id, subtotal, iva_total, total, metodo_pago) 
                    VALUES (:numero_ticket, :fecha, :empresa_id, :cliente_id, :subtotal, :iva_total, :total, :metodo_pago)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':numero_ticket' => $ticket->getNumeroTicket(),
                ':fecha' => $ticket->getFecha()->format('Y-m-d H:i:s'),
                ':empresa_id' => $ticket->getEmpresa()->getId(),
                ':cliente_id' => $ticket->getCliente() ? $ticket->getCliente()->getId() : null,
                ':subtotal' => $ticket->getSubtotal(),
                ':iva_total' => $ticket->getIvaTotal(),
                ':total' => $ticket->getTotal(),
                ':metodo_pago' => $ticket->getMetodoPago()
            ]);

            $ticketId = $this->db->lastInsertId();

            // Insertar los items del ticket
            foreach ($ticket->getItems() as $item) {
                $sql = "INSERT INTO ticket_items (ticket_id, producto_id, cantidad, precio_unitario, subtotal, iva_importe, total) 
                        VALUES (:ticket_id, :producto_id, :cantidad, :precio_unitario, :subtotal, :iva_importe, :total)";

                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':ticket_id' => $ticketId,
                    ':producto_id' => $item->getProducto()->getId(),
                    ':cantidad' => $item->getCantidad(),
                    ':precio_unitario' => $item->getPrecioUnitario(),
                    ':subtotal' => $item->getSubtotal(),
                    ':iva_importe' => $item->getIvaImporte(),
                    ':total' => $item->getTotal()
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new Exception("Error al guardar el ticket: " . $e->getMessage());
        }
    }

    public function buscarPorId($id)
    {
        try {
            $sql = "SELECT * FROM tickets WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->crearTicketDesdeArray($row);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar el ticket: " . $e->getMessage());
        }
    }

    public function buscarPorNumeroTicket($numeroTicket)
    {
        try {
            $sql = "SELECT * FROM tickets WHERE numero_ticket = :numero_ticket";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':numero_ticket' => $numeroTicket]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->crearTicketDesdeArray($row);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar el ticket: " . $e->getMessage());
        }
    }

    public function buscarTicketsSinFactura()
    {
        try {
            $sql = "SELECT t.* FROM tickets t 
                    LEFT JOIN facturas f ON t.id = f.ticket_id 
                    WHERE f.id IS NULL AND t.cliente_id IS NOT NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $tickets = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tickets[] = $this->crearTicketDesdeArray($row);
            }
            return $tickets;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar tickets sin factura: " . $e->getMessage());
        }
    }

    public function buscarPorFiltros($filtros)
    {
        try {
            $sql = "SELECT * FROM tickets WHERE 1=1";
            $params = [];

            if (!empty($filtros['fecha'])) {
                $sql .= " AND DATE(fecha) = :fecha";
                $params[':fecha'] = $filtros['fecha'];
            }

            if (!empty($filtros['numeroTicket'])) {
                $sql .= " AND numero_ticket LIKE :numeroTicket";
                $params[':numeroTicket'] = '%' . $filtros['numeroTicket'] . '%';
            }

            if (!empty($filtros['metodoPago'])) {
                $sql .= " AND metodo_pago = :metodoPago";
                $params[':metodoPago'] = $filtros['metodoPago'];
            }

            $sql .= " ORDER BY fecha DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            $tickets = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tickets[] = $this->crearTicketDesdeArray($row);
            }
            return $tickets;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar tickets: " . $e->getMessage());
        }
    }

    private function cargarItems($ticketId)
    {
        $sql = "SELECT ti.*, p.* FROM ticket_items ti 
                INNER JOIN productos p ON ti.producto_id = p.id 
                WHERE ti.ticket_id = :ticket_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ticket_id' => $ticketId]);

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $producto = new Producto(
                $row['codigo'],
                $row['nombre'],
                $row['precio'],
                $row['iva'],
                $row['descripcion'],
                $row['stock']
            );

            // Establecer el ID del producto
            $reflector = new ReflectionClass('Producto');
            $property = $reflector->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($producto, $row['producto_id']);

            $item = new TicketItem($producto, $row['cantidad']);
            $items[] = $item;
        }

        return $items;
    }

    private function crearTicketDesdeArray($array)
    {
        $empresa = $this->empresaRepository->buscarPorId($array['empresa_id']);
        $ticket = new Ticket($empresa);

        // Establecer los datos básicos del ticket
        $reflector = new ReflectionClass('Ticket');

        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($ticket, $array['id']);

        $property = $reflector->getProperty('numeroTicket');
        $property->setAccessible(true);
        $property->setValue($ticket, $array['numero_ticket']);

        $property = $reflector->getProperty('fecha');
        $property->setAccessible(true);
        $property->setValue($ticket, new DateTime($array['fecha']));

        $property = $reflector->getProperty('subtotal');
        $property->setAccessible(true);
        $property->setValue($ticket, $array['subtotal']);

        $property = $reflector->getProperty('ivaTotal');
        $property->setAccessible(true);
        $property->setValue($ticket, $array['iva_total']);

        $property = $reflector->getProperty('total');
        $property->setAccessible(true);
        $property->setValue($ticket, $array['total']);

        $property = $reflector->getProperty('metodoPago');
        $property->setAccessible(true);
        $property->setValue($ticket, $array['metodo_pago']);

        // Cargar el cliente si existe
        if ($array['cliente_id']) {
            $cliente = $this->clienteRepository->buscarPorId($array['cliente_id']);
            $property = $reflector->getProperty('cliente');
            $property->setAccessible(true);
            $property->setValue($ticket, $cliente);
        }

        // Cargar los items del ticket
        $items = $this->cargarItems($array['id']);
        $property = $reflector->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($ticket, $items);

        return $ticket;
    }
}
