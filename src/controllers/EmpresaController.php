<?php

class EmpresaController extends BaseCrudController
{
    protected function initializeService()
    {
        $this->service = new EmpresaService();
        $this->entityName = 'Empresa';
    }

    protected function validateData(array $data): array
    {
        $errors = [];
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        if (empty($data['cif'])) {
            $errors[] = 'El CIF es requerido';
        }
        if (!empty($errors)) {
            throw new Exception(implode('. ', $errors));
        }
        return [
            'nombre' => $data['nombre'],
            'cif' => $data['cif'],
            'direccion' => $data['direccion'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'logo' => $data['logo'] ?? null
        ];
    }

    protected function prepareDataForResponse($empresa): array
    {
        if (!$empresa) {
            return [];
        }
        return [
            'id' => $empresa->getId(),
            'nombre' => $empresa->getNombre(),
            'cif' => $empresa->getCif(),
            'direccion' => $empresa->getDireccion(),
            'telefono' => $empresa->getTelefono(),
            'email' => $empresa->getEmail(),
            'logo' => $empresa->getLogo(),
        ];
    }
}
