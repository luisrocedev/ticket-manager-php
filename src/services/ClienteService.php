<?php

class ClienteService extends BaseService
{
    protected function initializeRepository()
    {
        $this->repository = new ClienteRepository();
    }

    protected function createEntity(array $data)
    {
        return new Cliente(
            $data['nombre'],
            $data['apellidos'] ?? '',
            $data['dni_cif'],
            $data['direccion'] ?? null,
            $data['telefono'] ?? null,
            $data['email'] ?? null
        );
    }

    protected function updateEntity($cliente, array $data)
    {
        if (!$cliente) {
            throw new Exception("Cliente no encontrado");
        }

        $cliente->setNombre($data['nombre']);
        $cliente->setApellidos($data['apellidos'] ?? '');
        $cliente->setDniCif($data['dni_cif']);
        $cliente->setDireccion($data['direccion'] ?? null);
        $cliente->setTelefono($data['telefono'] ?? null);
        $cliente->setEmail($data['email'] ?? null);

        return $cliente;
    }

    public function buscarPorDniCif($dniCif)
    {
        return $this->repository->buscarPorDniCif($dniCif);
    }

    public function crear(array $data)
    {
        $cliente = $this->createEntity($data);
        if (!$this->repository->crear($cliente)) {
            throw new Exception("Error al crear el cliente");
        }
        return $this->buscarPorDniCif($cliente->getDniCif());
    }

    public function actualizar($id, array $data)
    {
        $cliente = $this->buscarPorId($id);
        if (!$cliente) {
            throw new Exception("Cliente no encontrado");
        }

        $cliente = $this->updateEntity($cliente, $data);
        if (!$this->repository->actualizar($cliente)) {
            throw new Exception("Error al actualizar el cliente");
        }

        return $cliente;
    }
}
