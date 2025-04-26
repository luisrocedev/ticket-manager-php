<?php
// Configuración específica para la vista de productos
$entityName = 'Producto';
$entityEndpoint = 'productos';

// Definir las columnas que se mostrarán en la tabla
$columns = [
    'codigo' => ['label' => 'Código', 'sortable' => true, 'type' => 'text'],
    'nombre' => ['label' => 'Nombre', 'sortable' => true, 'type' => 'text'],
    'descripcion' => ['label' => 'Descripción', 'sortable' => false, 'type' => 'text'],
    'precio' => ['label' => 'Precio', 'sortable' => true, 'type' => 'currency'],
    'iva' => ['label' => 'IVA (%)', 'sortable' => true, 'type' => 'number'],
    'stock' => ['label' => 'Stock', 'sortable' => true, 'type' => 'number']
];

// Definir los campos del formulario
$formFields = [
    [
        'name' => 'codigo',
        'label' => 'Código',
        'type' => 'text',
        'required' => true,
        'help' => 'Código único del producto'
    ],
    [
        'name' => 'nombre',
        'label' => 'Nombre',
        'type' => 'text',
        'required' => true
    ],
    [
        'name' => 'descripcion',
        'label' => 'Descripción',
        'type' => 'textarea',
        'required' => false
    ],
    [
        'name' => 'precio',
        'label' => 'Precio',
        'type' => 'number',
        'required' => true,
        'min' => '0',
        'step' => '0.01',
        'help' => 'Precio sin IVA'
    ],
    [
        'name' => 'iva',
        'label' => 'IVA (%)',
        'type' => 'number',
        'required' => true,
        'min' => '0',
        'max' => '100',
        'value' => '21'
    ],
    [
        'name' => 'stock',
        'label' => 'Stock',
        'type' => 'number',
        'required' => true,
        'min' => '0',
        'value' => '0'
    ]
];

// Incluir la vista base CRUD con la ruta absoluta
include(__DIR__ . '/../shared/crud.php');
