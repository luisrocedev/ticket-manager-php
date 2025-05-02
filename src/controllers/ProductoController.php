<?php

class ProductoController extends BaseCrudController
{
    protected function initializeService()
    {
        $this->service = new ProductoService();
        $this->entityName = 'Producto';
    }

    protected function validateData(array $data): array
    {
        $errors = [];

        if (empty($data['codigo'])) {
            $errors[] = 'El código es requerido';
        }
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        if (!isset($data['precio']) || $data['precio'] < 0) {
            $errors[] = 'El precio debe ser mayor o igual a 0';
        }
        if (!isset($data['iva']) || $data['iva'] < 0 || $data['iva'] > 100) {
            $errors[] = 'El IVA debe estar entre 0 y 100';
        }
        if (!isset($data['stock']) || $data['stock'] < 0) {
            $errors[] = 'El stock debe ser mayor o igual a 0';
        }

        if (!empty($errors)) {
            throw new Exception(implode('. ', $errors));
        }

        return [
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? '',
            'precio' => (float) $data['precio'],
            'iva' => (float) $data['iva'],
            'stock' => (int) $data['stock']
        ];
    }

    protected function prepareDataForResponse($producto): array
    {
        return [
            'id' => $producto->getId(),
            'codigo' => $producto->getCodigo(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'iva' => $producto->getIva(),
            'stock' => $producto->getStock()
        ];
    }

    public function actualizarStock($id, $cantidad)
    {
        try {
            $producto = $this->service->actualizarStock($id, $cantidad);
            return $this->jsonResponse([
                'success' => true,
                'data' => $this->prepareDataForResponse($producto),
                'message' => 'Stock actualizado correctamente'
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function buscar($params = [])
    {
        try {
            $resultados = $this->service->buscar($params);
            $data = array_map(function ($producto) {
                return $this->prepareDataForResponse($producto);
            }, $resultados);
            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([]);
        }
    }
}
