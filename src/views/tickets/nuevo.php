<?php
if (!isset($ticket)) {
    throw new Exception("No se ha inicializado el ticket correctamente");
}
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-cash-register"></i> Nuevo Ticket</h2>
        </div>
        <div class="col-md-4 text-end">
            <span class="h4">Ticket #: <span id="numeroTicket" class="text-primary"><?php echo $ticket->getNumeroTicket(); ?></span></span>
        </div>
    </div>

    <div class="row">
        <!-- Panel izquierdo - Búsqueda y lista de productos -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto por nombre o código...">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="buscarProducto()">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaProductos">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los productos se cargarán dinámicamente aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Productos en el ticket actual -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Productos en el ticket</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tablaTicket">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>IVA</th>
                                    <th>Subtotal</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los items del ticket se cargarán aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho - Resumen y acciones -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Resumen del Ticket</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Subtotal:</label>
                        <h4 id="subtotal">0.00 €</h4>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IVA Total:</label>
                        <h4 id="ivaTotal">0.00 €</h4>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Total:</label>
                        <h3 id="total" class="text-primary">0.00 €</h3>
                    </div>

                    <div class="mb-3">
                        <label for="metodoPago" class="form-label">Método de Pago:</label>
                        <select class="form-select" id="metodoPago">
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dniCliente" class="form-label">DNI/CIF Cliente (opcional):</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dniCliente" placeholder="Para factura...">
                            <button class="btn btn-outline-secondary" type="button" onclick="buscarCliente()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg" onclick="finalizarTicket()">
                            <i class="fas fa-check"></i> Finalizar Ticket
                        </button>
                        <button class="btn btn-danger" onclick="cancelarTicket()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cantidad de producto -->
<div class="modal fade" id="modalCantidad" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cantidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="productoId">
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad" min="1" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="agregarProductoATicket()">Agregar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Funciones JavaScript para la gestión del ticket
    let ticketItems = [];

    function buscarProducto() {
        const busqueda = document.getElementById('buscarProducto').value;
        fetch(`/GitHub/ticketscompra/api/productos/buscar?q=${encodeURIComponent(busqueda)}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#tablaProductos tbody');
                tbody.innerHTML = '';
                data.forEach(producto => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${producto.codigo}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.precio.toFixed(2)} €</td>
                    <td>${producto.stock}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="seleccionarProducto(${producto.id})">
                            <i class="fas fa-plus"></i>
                        </button>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            });
    }

    function seleccionarProducto(productoId) {
        document.getElementById('productoId').value = productoId;
        new bootstrap.Modal(document.getElementById('modalCantidad')).show();
    }

    function agregarProductoATicket() {
        const productoId = document.getElementById('productoId').value;
        const cantidad = document.getElementById('cantidad').value;

        fetch('/GitHub/ticketscompra/api/tickets/agregar-producto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ticketId: document.getElementById('numeroTicket').textContent,
                    productoId: productoId,
                    cantidad: cantidad
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    actualizarTablaTicket(data.ticket);
                    bootstrap.Modal.getInstance(document.getElementById('modalCantidad')).hide();
                } else {
                    alert(data.error);
                }
            });
    }

    function buscarCliente() {
        const dni = document.getElementById('dniCliente').value;
        if (dni) {
            fetch(`/GitHub/ticketscompra/api/clientes/buscar/${dni}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Cliente encontrado: ${data.cliente.nombre} ${data.cliente.apellidos}`);
                    } else {
                        alert('Cliente no encontrado');
                    }
                });
        }
    }

    function finalizarTicket() {
        const metodoPago = document.getElementById('metodoPago').value;
        const dniCliente = document.getElementById('dniCliente').value;

        fetch('/GitHub/ticketscompra/api/tickets/finalizar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    ticketId: document.getElementById('numeroTicket').textContent,
                    metodoPago: metodoPago,
                    dniCliente: dniCliente || null
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Imprimir ticket
                    imprimirTicket(data.impresion);
                    // Redireccionar a la lista de tickets
                    window.location.href = '/GitHub/ticketscompra/tickets/lista';
                } else {
                    alert(data.error);
                }
            });
    }

    function cancelarTicket() {
        if (confirm('¿Está seguro de que desea cancelar este ticket?')) {
            window.location.href = '/GitHub/ticketscompra/tickets/lista';
        }
    }

    function imprimirTicket(contenido) {
        const ventanaImpresion = window.open('', '_blank');
        ventanaImpresion.document.write(`
        <pre style="font-family: monospace;">
            ${contenido}
        </pre>
    `);
        ventanaImpresion.print();
        ventanaImpresion.close();
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        // Configurar el buscador de productos
        const buscarInput = document.getElementById('buscarProducto');
        let timeoutId;

        buscarInput.addEventListener('input', () => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(buscarProducto, 300);
        });
    });
</script>