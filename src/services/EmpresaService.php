<?php

class EmpresaService extends BaseService
{
    protected function initializeRepository()
    {
        $this->repository = new EmpresaRepository();
    }

    protected function createEntity(array $data)
    {
        return new Empresa(
            $data['nombre'],
            $data['cif'],
            $data['direccion'] ?? null,
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['logo'] ?? null
        );
    }

    protected function updateEntity($empresa, array $data)
    {
        if (!$empresa) {
            throw new Exception("Empresa no encontrada");
        }
        $empresa->setNombre($data['nombre']);
        $empresa->setCif($data['cif']);
        $empresa->setDireccion($data['direccion'] ?? null);
        $empresa->setTelefono($data['telefono'] ?? null);
        $empresa->setEmail($data['email'] ?? null);
        $empresa->setLogo($data['logo'] ?? null);

        return $empresa;
    }
}