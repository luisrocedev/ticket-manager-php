<?php

class EmpresaRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function crear(Empresa $empresa)
    {
        $sql = "INSERT INTO empresas (nombre, cif, direccion, telefono, email, logo) 
                VALUES (:nombre, :cif, :direccion, :telefono, :email, :logo)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $empresa->getNombre(),
            ':cif' => $empresa->getCif(),
            ':direccion' => $empresa->getDireccion(),
            ':telefono' => $empresa->getTelefono(),
            ':email' => $empresa->getEmail(),
            ':logo' => $empresa->getLogo()
        ]);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM empresas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $this->crearEmpresaDesdeArray($row);
        }
        return null;
    }

    public function actualizar(Empresa $empresa)
    {
        $sql = "UPDATE empresas 
                SET nombre = :nombre, 
                    cif = :cif, 
                    direccion = :direccion, 
                    telefono = :telefono, 
                    email = :email, 
                    logo = :logo 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $empresa->getId(),
            ':nombre' => $empresa->getNombre(),
            ':cif' => $empresa->getCif(),
            ':direccion' => $empresa->getDireccion(),
            ':telefono' => $empresa->getTelefono(),
            ':email' => $empresa->getEmail(),
            ':logo' => $empresa->getLogo()
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM empresas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function listarTodas()
    {
        $sql = "SELECT * FROM empresas";
        $stmt = $this->db->query($sql);

        $empresas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empresas[] = $this->crearEmpresaDesdeArray($row);
        }

        return $empresas;
    }

    // Alias para BaseService::listarTodos
    public function listarTodos()
    {
        return $this->listarTodas();
    }

    private function crearEmpresaDesdeArray($array)
    {
        $empresa = new Empresa(
            $array['nombre'],
            $array['cif'],
            $array['direccion'],
            $array['telefono'],
            $array['email'],
            $array['logo']
        );
        // Establecer el ID después de crear el objeto
        $reflector = new ReflectionClass('Empresa');
        $property = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($empresa, $array['id']);

        return $empresa;
    }
}
