<?php
// Configuración específica para la vista de clientes
$entityName = 'Cliente';
$entityEndpoint = 'clientes';

// Definir las columnas que se mostrarán en la tabla
$columns = [
    'dni_cif' => ['label' => 'DNI/CIF', 'sortable' => true, 'type' => 'text'],
    'nombre' => ['label' => 'Nombre', 'sortable' => true, 'type' => 'text'],
    'apellidos' => ['label' => 'Apellidos', 'sortable' => true, 'type' => 'text'],
    'telefono' => ['label' => 'Teléfono', 'sortable' => false, 'type' => 'text'],
    'email' => ['label' => 'Email', 'sortable' => false, 'type' => 'text']
];

// Definir los campos del formulario
$formFields = [
    [
        'name' => 'dni_cif',
        'label' => 'DNI/CIF',
        'type' => 'text',
        'required' => true,
        'help' => 'Documento de identidad del cliente'
    ],
    [
        'name' => 'nombre',
        'label' => 'Nombre',
        'type' => 'text',
        'required' => true
    ],
    [
        'name' => 'apellidos',
        'label' => 'Apellidos',
        'type' => 'text',
        'required' => false
    ],
    [
        'name' => 'direccion',
        'label' => 'Dirección',
        'type' => 'text',
        'required' => false
    ],
    [
        'name' => 'telefono',
        'label' => 'Teléfono',
        'type' => 'tel',
        'required' => false
    ],
    [
        'name' => 'email',
        'label' => 'Email',
        'type' => 'email',
        'required' => false
    ]
];

// Incluir la vista base CRUD
include(__DIR__ . '/../shared/crud.php');
