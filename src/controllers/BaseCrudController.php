<?php

abstract class BaseCrudController
{
    protected $service;
    protected $entityName;

    public function __construct()
    {
        $this->initializeService();
    }

    abstract protected function initializeService();
    abstract protected function validateData(array $data): array;
    abstract protected function prepareDataForResponse($entity): array;

    public function renderListView($data = [])
    {
        $entityData = $this->service->listarTodos();
        return array_merge([
            'titulo' => "Gestión de {$this->entityName}s",
            'items' => array_map([$this, 'prepareDataForResponse'], $entityData)
        ], $data);
    }

    public function index()
    {
        try {
            $items = $this->service->listarTodos();
            $mapped = array_map([$this, 'prepareDataForResponse'], $items);
            return $this->jsonResponse([
                'success' => true,
                'data' => $mapped,
                'total' => count($mapped)
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $item = $this->service->buscarPorId($id);
            if (!$item) {
                throw new Exception("{$this->entityName} no encontrado");
            }
            return $this->jsonResponse([
                'success' => true,
                'data' => $this->prepareDataForResponse($item)
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(array $data)
    {
        try {
            $validatedData = $this->validateData($data);
            $item = $this->service->crear($validatedData);
            return $this->jsonResponse([
                'success' => true,
                'data' => $this->prepareDataForResponse($item),
                'message' => "{$this->entityName} creado correctamente"
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update($id, array $data)
    {
        try {
            $validatedData = $this->validateData($data);
            $item = $this->service->actualizar($id, $validatedData);
            return $this->jsonResponse([
                'success' => true,
                'data' => $this->prepareDataForResponse($item),
                'message' => "{$this->entityName} actualizado correctamente"
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->eliminar($id);
            return $this->jsonResponse([
                'success' => true,
                'message' => "{$this->entityName} eliminado correctamente"
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function jsonResponse($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    protected function buscar(array $criterios)
    {
        try {
            $items = $this->service->buscar($criterios);
            return $this->jsonResponse([
                'success' => true,
                'data' => array_map([$this, 'prepareDataForResponse'], $items)
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
