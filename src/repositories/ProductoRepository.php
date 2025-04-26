<?php

class ProductoRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function crear(Producto $producto) {
        $sql = "INSERT INTO productos (codigo, nombre, descripcion, precio, iva, stock) 
                VALUES (:codigo, :nombre, :descripcion, :precio, :iva, :stock)";
                
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            ':codigo' => $producto->getCodigo(),
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
            ':iva' => $producto->getIva(),
            ':stock' => $producto->getStock()
        ]);
        
        // Obtener el ID insertado y devolverlo
        return $this->buscarPorId($this->db->lastInsertId());
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $this->crearProductoDesdeArray($row);
        }
        return null;
    }
    
    public function buscarPorCodigo($codigo) {
        $sql = "SELECT * FROM productos WHERE codigo = :codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':codigo' => $codigo]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $this->crearProductoDesdeArray($row);
        }
        return null;
    }
    
    public function actualizar(Producto $producto) {
        $sql = "UPDATE productos 
                SET codigo = :codigo,
                    nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    iva = :iva,
                    stock = :stock
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $producto->getId(),
            ':codigo' => $producto->getCodigo(),
            ':nombre' => $producto->getNombre(),
            ':descripcion' => $producto->getDescripcion(),
            ':precio' => $producto->getPrecio(),
            ':iva' => $producto->getIva(),
            ':stock' => $producto->getStock()
        ]);
    }
    
    public function eliminar($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function listarTodos() {
        $sql = "SELECT * FROM productos";
        $stmt = $this->db->query($sql);
        
        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = $this->crearProductoDesdeArray($row);
        }
        
        return $productos;
    }
    
    public function buscar(array $criterios) {
        $sql = "SELECT * FROM productos WHERE 1=1";
        $params = [];
        
        if (!empty($criterios['busqueda'])) {
            $sql .= " AND (codigo LIKE :busqueda OR nombre LIKE :busqueda)";
            $params[':busqueda'] = "%{$criterios['busqueda']}%";
        }
        
        if (isset($criterios['stock_min'])) {
            $sql .= " AND stock >= :stock_min";
            $params[':stock_min'] = $criterios['stock_min'];
        }
        
        if (isset($criterios['precio_min'])) {
            $sql .= " AND precio >= :precio_min";
            $params[':precio_min'] = $criterios['precio_min'];
        }
        
        if (isset($criterios['precio_max'])) {
            $sql .= " AND precio <= :precio_max";
            $params[':precio_max'] = $criterios['precio_max'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productos[] = $this->crearProductoDesdeArray($row);
        }
        
        return $productos;
    }
    
    private function crearProductoDesdeArray($array) {
        $producto = new Producto(
            $array['codigo'],
            $array['nombre'],
            $array['precio'],
            $array['iva'],
            $array['descripcion'],
            $array['stock']
        );
        
        $reflector = new ReflectionClass('Producto');
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($producto, $array['id']);
        
        return $producto;
    }
}