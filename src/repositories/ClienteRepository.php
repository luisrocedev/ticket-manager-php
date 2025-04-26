<?php

class ClienteRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function crear(Cliente $cliente)
    {
        try {
            $sql = "INSERT INTO clientes (nombre, apellidos, dni_cif, direccion, telefono, email) 
                    VALUES (:nombre, :apellidos, :dni_cif, :direccion, :telefono, :email)";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nombre' => $cliente->getNombre(),
                ':apellidos' => $cliente->getApellidos(),
                ':dni_cif' => $cliente->getDniCif(),
                ':direccion' => $cliente->getDireccion(),
                ':telefono' => $cliente->getTelefono(),
                ':email' => $cliente->getEmail()
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código de error para duplicado
                throw new Exception("Ya existe un cliente con ese DNI/CIF");
            }
            throw new Exception("Error al crear el cliente: " . $e->getMessage());
        }
    }

    public function buscarPorId($id)
    {
        try {
            $sql = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->crearClienteDesdeArray($row);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar el cliente: " . $e->getMessage());
        }
    }

    public function buscarPorDniCif($dniCif)
    {
        try {
            $sql = "SELECT * FROM clientes WHERE dni_cif = :dni_cif";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':dni_cif' => $dniCif]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->crearClienteDesdeArray($row);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al buscar el cliente: " . $e->getMessage());
        }
    }

    public function actualizar(Cliente $cliente)
    {
        try {
            $sql = "UPDATE clientes 
                    SET nombre = :nombre,
                        apellidos = :apellidos,
                        dni_cif = :dni_cif,
                        direccion = :direccion,
                        telefono = :telefono,
                        email = :email
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $cliente->getId(),
                ':nombre' => $cliente->getNombre(),
                ':apellidos' => $cliente->getApellidos(),
                ':dni_cif' => $cliente->getDniCif(),
                ':direccion' => $cliente->getDireccion(),
                ':telefono' => $cliente->getTelefono(),
                ':email' => $cliente->getEmail()
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Código de error para duplicado
                throw new Exception("Ya existe un cliente con ese DNI/CIF");
            }
            throw new Exception("Error al actualizar el cliente: " . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $sql = "DELETE FROM clientes WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([':id' => $id])) {
                if ($stmt->rowCount() === 0) {
                    throw new Exception("Cliente no encontrado");
                }
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // Si hay error por restricción de clave foránea
            if ($e->getCode() == 23000) {
                throw new Exception("No se puede eliminar el cliente porque tiene registros asociados");
            }
            throw new Exception("Error al eliminar el cliente: " . $e->getMessage());
        }
    }

    public function listarTodos()
    {
        try {
            $sql = "SELECT * FROM clientes ORDER BY nombre, apellidos";
            $stmt = $this->db->query($sql);

            $clientes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $clientes[] = $this->crearClienteDesdeArray($row);
            }

            return $clientes;
        } catch (PDOException $e) {
            throw new Exception("Error al listar los clientes: " . $e->getMessage());
        }
    }

    private function crearClienteDesdeArray($array)
    {
        $cliente = new Cliente(
            $array['nombre'],
            $array['apellidos'],
            $array['dni_cif'],
            $array['direccion'],
            $array['telefono'],
            $array['email']
        );

        $reflector = new ReflectionClass('Cliente');
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($cliente, $array['id']);

        return $cliente;
    }
}
