<?php
// Configuración específica para la vista de empresas
$entityName = 'Empresa';
$entityEndpoint = 'empresas';

// Definir las columnas que se mostrarán en la tabla
$columns = [
    'id' => ['label' => 'ID', 'sortable' => true, 'type' => 'number'],
    'nombre' => ['label' => 'Nombre', 'sortable' => true, 'type' => 'text'],
    'cif' => ['label' => 'CIF', 'sortable' => true, 'type' => 'text'],
    'direccion' => ['label' => 'Dirección', 'sortable' => false, 'type' => 'text'],
    'telefono' => ['label' => 'Teléfono', 'sortable' => false, 'type' => 'text'],
    'email' => ['label' => 'Email', 'sortable' => false, 'type' => 'text'],
    'logo' => ['label' => 'Logo', 'sortable' => false, 'type' => 'text']
];

// Definir los campos del formulario
$formFields = [
    ['name' => 'nombre',    'label' => 'Nombre', 'type' => 'text',  'required' => true],
    ['name' => 'cif',       'label' => 'CIF',    'type' => 'text',  'required' => true],
    ['name' => 'direccion', 'label' => 'Dirección', 'type' => 'text', 'required' => false],
    ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'required' => false],
    ['name' => 'email',    'label' => 'Email',   'type' => 'email', 'required' => false],
    ['name' => 'logo',     'label' => 'Logo',    'type' => 'text',  'required' => false]
];

// Incluir la vista base CRUD
include(__DIR__ . '/../shared/crud.php');
