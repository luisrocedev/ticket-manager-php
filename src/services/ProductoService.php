<?php

class ProductoService extends BaseService {
    protected function initializeRepository() {
        $this->repository = new ProductoRepository();
    }
    
    protected function createEntity(array $data) {
        return new Producto(
            $data['codigo'],
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['iva'],
            $data['stock']
        );
    }
    
    protected function updateEntity($producto, array $data) {
        $producto->setCodigo($data['codigo']);
        $producto->setNombre($data['nombre']);
        $producto->setDescripcion($data['descripcion']);
        $producto->setPrecio($data['precio']);
        $producto->setIva($data['iva']);
        $producto->setStock($data['stock']);
        return $producto;
    }
    
    public function actualizarStock($id, $cantidad) {
        $producto = $this->buscarPorId($id);
        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }
        
        $nuevoStock = $producto->getStock() + $cantidad;
        if ($nuevoStock < 0) {
            throw new Exception("No hay suficiente stock disponible");
        }
        
        $producto->setStock($nuevoStock);
        $this->repository->actualizar($producto);
        return $producto;
    }
    
    public function buscarPorCodigo($codigo) {
        return $this->repository->buscarPorCodigo($codigo);
    }
    
    public function buscar(array $criterios) {
        $resultados = $this->repository->buscar($criterios);
        if (isset($criterios['ordenar'])) {
            $this->ordenarResultados($resultados, $criterios['ordenar'], $criterios['orden'] ?? 'asc');
        }
        return $resultados;
    }
    
    private function ordenarResultados(&$resultados, $campo, $orden) {
        usort($resultados, function($a, $b) use ($campo, $orden) {
            $valorA = $this->getValorCampo($a, $campo);
            $valorB = $this->getValorCampo($b, $campo);
            
            return $orden === 'asc' 
                ? $valorA <=> $valorB 
                : $valorB <=> $valorA;
        });
    }
    
    private function getValorCampo($producto, $campo) {
        switch ($campo) {
            case 'nombre':
                return $producto->getNombre();
            case 'codigo':
                return $producto->getCodigo();
            case 'precio':
                return $producto->getPrecio();
            case 'stock':
                return $producto->getStock();
            default:
                return null;
        }
    }
}