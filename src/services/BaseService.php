<?php

abstract class BaseService
{
    protected $repository;

    public function __construct()
    {
        $this->initializeRepository();
    }

    abstract protected function initializeRepository();

    public function listarTodos()
    {
        return $this->repository->listarTodos();
    }

    public function buscarPorId($id)
    {
        return $this->repository->buscarPorId($id);
    }

    public function crear(array $data)
    {
        return $this->repository->crear($this->createEntity($data));
    }

    public function actualizar($id, array $data)
    {
        $entity = $this->repository->buscarPorId($id);
        if (!$entity) {
            throw new Exception("Entidad no encontrada");
        }
        $this->updateEntity($entity, $data);
        return $this->repository->actualizar($entity);
    }

    public function eliminar($id)
    {
        return $this->repository->eliminar($id);
    }

    public function buscar(array $criterios)
    {
        return $this->repository->buscar($criterios);
    }

    abstract protected function createEntity(array $data);
    abstract protected function updateEntity($entity, array $data);
}
