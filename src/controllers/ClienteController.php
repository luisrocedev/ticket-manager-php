<?php

class ClienteController extends BaseCrudController
{
    protected function initializeService()
    {
        $this->service = new ClienteService();
        $this->entityName = 'Cliente';
    }

    protected function validateData(array $data): array
    {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        if (empty($data['dni_cif'])) {
            $errors[] = 'El DNI/CIF es requerido';
        }

        // Validar que el DNI/CIF no exista ya (excepto en actualizaciones)
        if (!empty($data['dni_cif'])) {
            $existente = $this->service->buscarPorDniCif($data['dni_cif']);
            if ($existente && (!isset($data['id']) || $existente->getId() != $data['id'])) {
                $errors[] = 'Ya existe un cliente con ese DNI/CIF';
            }
        }

        if (!empty($errors)) {
            throw new Exception(implode('. ', $errors));
        }

        return [
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'] ?? '',
            'dni_cif' => $data['dni_cif'],
            'direccion' => $data['direccion'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null
        ];
    }

    protected function prepareDataForResponse($cliente): array
    {
        if (!$cliente) {
            return [];
        }

        return [
            'id' => $cliente->getId(),
            'nombre' => $cliente->getNombre(),
            'apellidos' => $cliente->getApellidos(),
            'dni_cif' => $cliente->getDniCif(),
            'direccion' => $cliente->getDireccion(),
            'telefono' => $cliente->getTelefono(),
            'email' => $cliente->getEmail()
        ];
    }

    public function store(array $data)
    {
        try {
            header('Content-Type: application/json');
            $validatedData = $this->validateData($data);
            $item = $this->service->crear($validatedData);
            return json_encode([
                'success' => true,
                'data' => $this->prepareDataForResponse($item),
                'message' => "{$this->entityName} creado correctamente"
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update($id, array $data)
    {
        try {
            header('Content-Type: application/json');
            $validatedData = $this->validateData(array_merge($data, ['id' => $id]));
            $item = $this->service->actualizar($id, $validatedData);
            return json_encode([
                'success' => true,
                'data' => $this->prepareDataForResponse($item),
                'message' => "{$this->entityName} actualizado correctamente"
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            return json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Añadir método público para buscar por criterios
    public function buscar(array $params)
    {
        return parent::buscar($params);
    }
}
