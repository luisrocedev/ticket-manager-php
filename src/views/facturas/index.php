<?php
// Configuración específica para la vista de facturas
$entityName = 'Factura';
$entityEndpoint = 'facturas';

// Definir las columnas que se mostrarán en la tabla
$columns = [
    'numero_factura' => ['label' => 'Nº Factura', 'sortable' => true, 'type' => 'text'],
    'fecha_emision' => ['label' => 'Fecha', 'sortable' => true, 'type' => 'date'],
    'ticket' => ['label' => 'Nº Ticket', 'sortable' => false, 'type' => 'text'],
    'cliente' => ['label' => 'Cliente', 'sortable' => false, 'type' => 'text'],
    'total' => ['label' => 'Total', 'sortable' => true, 'type' => 'currency'],
    'estado' => ['label' => 'Estado', 'sortable' => true, 'type' => 'text']
];

// Definir los campos del formulario
$formFields = [
    [
        'name' => 'ticket_id',
        'label' => 'Ticket',
        'type' => 'select',
        'required' => true,
        'options' => [], // Se llenará dinámicamente con los tickets disponibles
        'help' => 'Seleccione el ticket para generar la factura'
    ],
    [
        'name' => 'fecha_emision',
        'label' => 'Fecha de Emisión',
        'type' => 'date',
        'required' => true,
        'value' => date('Y-m-d')
    ],
    [
        'name' => 'estado',
        'label' => 'Estado',
        'type' => 'select',
        'required' => true,
        'options' => [
            ['value' => 'emitida', 'label' => 'Emitida'],
            ['value' => 'pagada', 'label' => 'Pagada'],
            ['value' => 'anulada', 'label' => 'Anulada']
        ]
    ]
];

// Incluir la vista base CRUD
include(__DIR__ . '/../shared/crud.php');
?>

<script>
    // Cargar los tickets disponibles al iniciar
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const response = await fetch('/GitHub/ticketscompra/api/tickets/disponibles');
            const data = await response.json();

            if (data.success) {
                const ticketSelect = document.getElementById('ticket_id');
                if (ticketSelect) {
                    data.data.forEach(ticket => {
                        const option = document.createElement('option');
                        option.value = ticket.id;
                        option.textContent = `${ticket.numero_ticket} - ${ticket.cliente ? ticket.cliente.nombre : 'Sin cliente'} - ${new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(ticket.total)}`;
                        ticketSelect.appendChild(option);
                    });
                }
            }
        } catch (error) {
            console.error('Error al cargar tickets:', error);
        }
    });

    // Sobreescribir la función de renderizado para manejar los datos específicos de facturas
    function renderTable(data) {
        const tbody = document.getElementById('dataTableBody');
        tbody.innerHTML = '';

        data.forEach(item => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
            <td>${item.numero_factura}</td>
            <td>${new Date(item.fecha_emision).toLocaleDateString('es-ES')}</td>
            <td>${item.ticket.numero_ticket}</td>
            <td>${item.ticket.cliente ? item.ticket.cliente.nombre + ' ' + item.ticket.cliente.apellidos : '-'}</td>
            <td>${new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(item.ticket.total)}</td>
            <td>${item.estado}</td>
            <td>
                <button class="btn btn-sm btn-info" onclick="verFactura(${item.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-secondary" onclick="descargarPDF(${item.id})">
                    <i class="fas fa-file-pdf"></i>
                </button>
            </td>
        `;

            tbody.appendChild(tr);
        });
    }

    function verFactura(id) {
        window.location.href = `/GitHub/ticketscompra/facturas/${id}`;
    }

    function descargarPDF(id) {
        window.location.href = `/GitHub/ticketscompra/facturas/${id}/pdf`;
    }
</script>