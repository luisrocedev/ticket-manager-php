<?php

class FacturaRepository
{
    private $db;
    private $ticketRepository;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->ticketRepository = new TicketRepository();
    }

    public function crear(Factura $factura)
    {
        try {
            $sql = "INSERT INTO facturas (numero_factura, ticket_id, fecha_emision, estado) 
                    VALUES (:numero_factura, :ticket_id, :fecha_emision, :estado)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':numero_factura' => $factura->getNumeroFactura(),
                ':ticket_id' => $factura->getTicket()->getId(),
                ':fecha_emision' => $factura->getFechaEmision()->format('Y-m-d H:i:s'),
                ':estado' => $factura->getEstado()
            ]);
            // Retornar la factura creada
            $lastId = $this->db->lastInsertId();
            return $this->buscarPorId($lastId);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Ya existe una factura para este ticket");
            }
            throw new Exception("Error al crear la factura: " . $e->getMessage());
        }
    }

    public function buscarPorId($id)
    {
        try {
            $sql = "SELECT * FROM facturas WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->crearFacturaDesdeArray($row);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar la factura: " . $e->getMessage());
        }
    }

    public function buscarPorFiltros(array $filtros)
    {
        try {
            $sql = "SELECT * FROM facturas WHERE 1=1";
            $params = [];

            if (!empty($filtros['fecha_inicio'])) {
                $sql .= " AND DATE(fecha_emision) >= :fecha_inicio";
                $params[':fecha_inicio'] = $filtros['fecha_inicio'];
            }

            if (!empty($filtros['fecha_fin'])) {
                $sql .= " AND DATE(fecha_emision) <= :fecha_fin";
                $params[':fecha_fin'] = $filtros['fecha_fin'];
            }

            if (!empty($filtros['estado'])) {
                $sql .= " AND estado = :estado";
                $params[':estado'] = $filtros['estado'];
            }

            $sql .= " ORDER BY fecha_emision DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            $facturas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $facturas[] = $this->crearFacturaDesdeArray($row);
            }

            return $facturas;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar facturas: " . $e->getMessage());
        }
    }

    public function actualizar(Factura $factura)
    {
        try {
            $sql = "UPDATE facturas 
                    SET estado = :estado 
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $factura->getId(),
                ':estado' => $factura->getEstado()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar la factura: " . $e->getMessage());
        }
    }

    private function crearFacturaDesdeArray($array)
    {
        $ticket = $this->ticketRepository->buscarPorId($array['ticket_id']);
        if (!$ticket) {
            throw new Exception("No se encontró el ticket asociado a la factura");
        }

        $factura = new Factura($ticket);

        $reflector = new ReflectionClass('Factura');

        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($factura, $array['id']);

        $property = $reflector->getProperty('numeroFactura');
        $property->setAccessible(true);
        $property->setValue($factura, $array['numero_factura']);

        $property = $reflector->getProperty('fechaEmision');
        $property->setAccessible(true);
        $property->setValue($factura, new DateTime($array['fecha_emision']));

        $property = $reflector->getProperty('estado');
        $property->setAccessible(true);
        $property->setValue($factura, $array['estado']);

        return $factura;
    }
}
