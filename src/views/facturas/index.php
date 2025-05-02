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

// Definir los campos del formulario (solo ticket)
$formFields = [
    [
        'name' => 'ticket_id',
        'label' => 'Ticket',
        'type' => 'select',
        'required' => true,
        'options' => [], // Se llenará dinámicamente con los tickets disponibles
        'help' => 'Seleccione el ticket para generar la factura'
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
                    // Limpiar opciones existentes
                    ticketSelect.innerHTML = '<option value="" disabled selected>Seleccione un ticket</option>';
                    data.data.forEach(ticket => {
                        const option = document.createElement('option');
                        option.value = ticket.id;
                        option.textContent = `${ticket.numeroTicket} - ${ticket.cliente ? ticket.cliente.nombre : 'Sin cliente'} - ${new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(ticket.total)}`;
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
        // Obtener datos de la factura y renderizar en el modal
        fetch(`/GitHub/ticketscompra/api/facturas/${id}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    showError(data.error || 'No se pudo cargar la factura');
                    return;
                }
                const inv = data.data;
                let html = `
                    <h5>Factura ${inv.numero_factura}</h5>
                    <p><strong>Fecha:</strong> ${new Date(inv.fecha_emision).toLocaleDateString('es-ES')}</p>
                    <p><strong>Ticket:</strong> ${inv.ticket.numero_ticket}</p>
                    ${inv.ticket.cliente ? `<p><strong>Cliente:</strong> ${inv.ticket.cliente.nombre} ${inv.ticket.cliente.apellidos}</p>` : ''}
                    <table class="table">
                        <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                        <tbody>
                        ${inv.items.map(item => `
                            <tr>
                                <td>${item.producto}</td>
                                <td>${item.cantidad}</td>
                                <td>${new Intl.NumberFormat('es-ES',{style:'currency',currency:'EUR'}).format(item.precio)}</td>
                                <td>${new Intl.NumberFormat('es-ES',{style:'currency',currency:'EUR'}).format(item.subtotal)}</td>
                            </tr>
                        `).join('')}
                        </tbody>
                    </table>
                    <div class="text-end">
                        <strong>Total:</strong> ${new Intl.NumberFormat('es-ES',{style:'currency',currency:'EUR'}).format(inv.ticket.total)}
                    </div>
                `;
                document.getElementById('previewBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('previewModal')).show();
            })
            .catch(err => showError('Error al cargar la factura: ' + err.message));
    }

    function descargarPDF(id) {
        window.location.href = `/GitHub/ticketscompra/index.php/facturas/${id}/pdf`;
    }

    // Sobreescribir la función de guardar para generar factura
    async function saveEntity() {
        const ticketId = document.getElementById('ticket_id').value;
        try {
            const response = await fetch(`/GitHub/ticketscompra/api/facturas/generar/${ticketId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const result = await response.json();
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('entityModal')).hide();
                showSuccess(result.message);
                loadData(currentPage);
            } else {
                showError(result.error || 'Error al generar la factura');
            }
        } catch (error) {
            console.error('Error al generar factura:', error);
            showError(error.message);
        }
    }
</script>

<!-- Modal para vista previa de factura -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vista previa de factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="previewBody">
            </div>
        </div>
    </div>
</div>