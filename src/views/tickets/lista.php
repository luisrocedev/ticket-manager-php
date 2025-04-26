<?php if (!isset($tickets)) throw new Exception('No se han cargado los tickets'); ?>

<div class="container-fluid">
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><?php echo $titulo; ?></h2>
        </div>
        <div class="col-md-4">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="date" class="form-control" name="fecha" value="<?php echo $filtros['fecha']; ?>">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de tickets -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nº Ticket</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Método Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket->getNumeroTicket()); ?></td>
                        <td><?php echo $ticket->getFecha()->format('d/m/Y H:i'); ?></td>
                        <td><?php echo $ticket->getCliente() ? htmlspecialchars($ticket->getCliente()->getNombre() . ' ' . $ticket->getCliente()->getApellidos()) : '-'; ?></td>
                        <td><?php echo number_format($ticket->getTotal(), 2); ?> €</td>
                        <td><?php echo ucfirst($ticket->getMetodoPago()); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="verDetalles(<?php echo $ticket->getId(); ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary" onclick="reimprimirTicket(<?php echo $ticket->getId(); ?>)">
                                <i class="fas fa-print"></i>
                            </button>
                            <?php if ($ticket->getCliente()): ?>
                                <button class="btn btn-sm btn-success" onclick="generarFactura(<?php echo $ticket->getId(); ?>)">
                                    <i class="fas fa-file-invoice"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detallesContenido"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensajes -->
<div class="modal fade" id="mensajeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="mensajeContenido"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar los modales globalmente
        window.detallesModal = new bootstrap.Modal(document.getElementById('detallesModal'));
        window.mensajeModal = new bootstrap.Modal(document.getElementById('mensajeModal'));
    });

    function mostrarMensaje(mensaje, tipo = 'info') {
        const contenido = document.getElementById('mensajeContenido');
        contenido.innerHTML = `<div class="alert alert-${tipo === 'error' ? 'danger' : 'success'}">${mensaje}</div>`;
        window.mensajeModal.show();
    }

    async function verDetalles(id) {
        try {
            const response = await fetch(`/GitHub/ticketscompra/api/tickets/${id}/detalle`);
            const data = await response.json();

            if (data.success) {
                const ticket = data.data;
                let html = `
                <div class="mb-3">
                    <strong>Nº Ticket:</strong> ${ticket.numero_ticket}<br>
                    <strong>Fecha:</strong> ${new Date(ticket.fecha).toLocaleString('es-ES')}<br>
                    <strong>Cliente:</strong> ${ticket.cliente ? ticket.cliente.nombre + ' ' + ticket.cliente.apellidos : '-'}<br>
                    <strong>Método de Pago:</strong> ${ticket.metodo_pago}
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>`;

                ticket.items.forEach(item => {
                    html += `
                    <tr>
                        <td>${item.producto.nombre}</td>
                        <td>${item.cantidad}</td>
                        <td>${item.precio_unitario.toFixed(2)} €</td>
                        <td>${item.total.toFixed(2)} €</td>
                    </tr>`;
                });

                html += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td>${ticket.subtotal.toFixed(2)} €</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>IVA:</strong></td>
                            <td>${ticket.iva_total.toFixed(2)} €</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td>${ticket.total.toFixed(2)} €</td>
                        </tr>
                    </tfoot>
                </table>`;

                document.getElementById('detallesContenido').innerHTML = html;
                window.detallesModal.show();
            } else {
                mostrarMensaje('Error: ' + data.error, 'error');
            }
        } catch (error) {
            mostrarMensaje('Error al cargar los detalles del ticket', 'error');
        }
    }

    async function reimprimirTicket(id) {
        try {
            const response = await fetch(`/GitHub/ticketscompra/api/tickets/${id}/reimprimir`);
            const data = await response.json();

            if (data.success) {
                mostrarMensaje(data.message || 'Ticket reimpreso correctamente', 'success');
                // Aquí iría la lógica para imprimir el ticket
                console.log('Contenido del ticket:', data.data);
            } else {
                mostrarMensaje('Error: ' + data.error, 'error');
            }
        } catch (error) {
            mostrarMensaje('Error al reimprimir el ticket', 'error');
        }
    }

    async function generarFactura(id) {
        try {
            const response = await fetch(`/GitHub/ticketscompra/api/tickets/${id}/generar-factura`);
            const data = await response.json();

            if (data.success) {
                mostrarMensaje('Factura generada correctamente: ' + data.data.numero_factura, 'success');
            } else {
                mostrarMensaje('Error: ' + data.error, 'error');
            }
        } catch (error) {
            mostrarMensaje('Error al generar la factura', 'error');
        }
    }
</script>