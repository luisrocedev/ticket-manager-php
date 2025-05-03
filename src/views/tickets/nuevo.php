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
                        <label for="empresaSelect" class="form-label">Empresa:</label>
                        <select class="form-select mb-3" id="empresaSelect">
                            <?php foreach ($empresas as $emp): ?>
                                <option value="<?= $emp->getId() ?>" <?= $emp->getId() === $empresa->getId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($emp->getNombre(), ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="mb-3">
                            <label for="clienteSelect" class="form-label">Cliente:</label>
                            <select class="form-select mb-3" id="clienteSelect">
                                <option value="">-- Ninguno --</option>
                                <?php foreach ($clientes as $cli): ?>
                                    <option value="<?= $cli->getId() ?>">
                                        <?= htmlspecialchars($cli->getNombre() . ' ' . $cli->getApellidos(), ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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

<!-- Modal de vista previa de ticket -->
<div class="modal fade" id="ticketPreviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vista Previa del Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="ticketPreviewContent" style="font-family: monospace;"></pre>
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
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la respuesta: ${response.status} ${response.statusText}`);
                }
                return response.text().then(text => {
                    if (!text) return {};
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Error al analizar JSON:', text);
                        throw new Error('La respuesta no es un JSON válido');
                    }
                });
            })
            .then(data => {
                if (!data || !Array.isArray(data)) {
                    console.warn('La respuesta no es un array:', data);
                    return;
                }
                const tbody = document.querySelector('#tablaProductos tbody');
                tbody.innerHTML = '';
                data.forEach(producto => {
                    // Asegurar que el precio sea un número antes de usar toFixed()
                    const precio = parseFloat(producto.precio) || 0;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${producto.codigo || ''}</td>
                    <td>${producto.nombre || ''}</td>
                    <td>${precio.toFixed(2)} €</td>
                    <td>${producto.stock || 0}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="seleccionarProducto(${producto.id})">
                            <i class="fas fa-plus"></i>
                        </button>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            })
            .catch(error => {
                console.error('Error en buscarProducto:', error);
                alert('Error al buscar productos: ' + error.message);
            });
    }

    function seleccionarProducto(productoId) {
        document.getElementById('productoId').value = productoId;
        new bootstrap.Modal(document.getElementById('modalCantidad')).show();
    }

    async function agregarProductoATicket() {
        const productoId = parseInt(document.getElementById('productoId').value);
        const cantidad = parseInt(document.getElementById('cantidad').value);
        try {
            // Obtener detalles del producto
            const resp = await fetch(`/GitHub/ticketscompra/api/productos/${productoId}`);
            const result = await resp.json();
            if (!result.success) {
                throw new Error(result.error || 'No se pudo obtener el producto');
            }
            const producto = result.data;
            // Añadir al array de items
            ticketItems.push({
                id: producto.id,
                nombre: producto.nombre,
                precio: parseFloat(producto.precio),
                iva: parseFloat(producto.iva),
                cantidad: cantidad
            });
            // Actualizar la tabla y cerrar modal
            actualizarTablaTicket({
                items: ticketItems
            });
            bootstrap.Modal.getInstance(document.getElementById('modalCantidad')).hide();
        } catch (error) {
            console.error('Error al agregar el producto:', error);
            alert('Error al agregar el producto: ' + error.message);
        }
    }

    function buscarCliente() {
        const dni = document.getElementById('dniCliente').value;
        if (dni) {
            fetch(`/GitHub/ticketscompra/api/clientes/buscar/${dni}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error en la respuesta: ${response.status} ${response.statusText}`);
                    }
                    return response.text().then(text => {
                        if (!text) return {};
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Error al analizar JSON:', text);
                            throw new Error('La respuesta no es un JSON válido');
                        }
                    });
                })
                .then(data => {
                    if (data && data.success) {
                        alert(`Cliente encontrado: ${data.cliente.nombre} ${data.cliente.apellidos}`);
                    } else {
                        alert('Cliente no encontrado');
                    }
                })
                .catch(error => {
                    console.error('Error en buscarCliente:', error);
                    alert('Error al buscar cliente: ' + error.message);
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
                    empresa_id: document.getElementById('empresaSelect').value,
                    cliente_id: document.getElementById('clienteSelect').value || null,
                    items: ticketItems,
                    metodoPago: metodoPago,
                    dniCliente: dniCliente || null
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la respuesta: ${response.status} ${response.statusText}`);
                }
                return response.text().then(text => {
                    if (!text) return {};
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Error al analizar JSON:', text);
                        throw new Error('La respuesta no es un JSON válido');
                    }
                });
            })
            .then(data => {
                if (data && data.success) {
                    // Mostrar vista previa del ticket
                    document.getElementById('ticketPreviewContent').textContent = data.impresion;
                    new bootstrap.Modal(document.getElementById('ticketPreviewModal')).show();
                } else {
                    alert(data.error || 'Error al finalizar el ticket');
                }
            })
            .catch(error => {
                console.error('Error en finalizarTicket:', error);
                alert('Error al finalizar el ticket: ' + error.message);
            });
    }

    function cancelarTicket() {
        if (confirm('¿Está seguro de que desea cancelar este ticket?')) {
            window.location.href = '/GitHub/ticketscompra/tickets/lista';
        }
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

    // Función para actualizar la tabla del ticket con los productos agregados
    function actualizarTablaTicket(ticket) {
        if (!ticket || !ticket.items || !Array.isArray(ticket.items)) {
            console.error("Datos de ticket inválidos:", ticket);
            return;
        }

        const tbody = document.querySelector('#tablaTicket tbody');
        tbody.innerHTML = '';

        let subtotalGeneral = 0;
        let ivaTotalGeneral = 0;

        ticket.items.forEach(item => {
            // Asegurar que todos los valores numéricos se traten como números
            const cantidad = parseFloat(item.cantidad);
            const precioUnitario = parseFloat(item.precio);
            const ivaPorcentaje = parseFloat(item.iva);

            // Calcular los valores
            const subtotal = cantidad * precioUnitario;
            const ivaValor = (subtotal * ivaPorcentaje) / 100;
            const total = subtotal + ivaValor;

            // Acumular totales
            subtotalGeneral += subtotal;
            ivaTotalGeneral += ivaValor;

            // Crear la fila
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.nombre}</td>
                <td>${cantidad}</td>
                <td>${precioUnitario.toFixed(2)} €</td>
                <td>${ivaPorcentaje}%</td>
                <td>${subtotal.toFixed(2)} €</td>
                <td>${total.toFixed(2)} €</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Actualizar el resumen
        const totalGeneral = subtotalGeneral + ivaTotalGeneral;
        document.getElementById('subtotal').textContent = subtotalGeneral.toFixed(2) + ' €';
        document.getElementById('ivaTotal').textContent = ivaTotalGeneral.toFixed(2) + ' €';
        document.getElementById('total').textContent = totalGeneral.toFixed(2) + ' €';
    }

    // Función para eliminar un producto del ticket
    function eliminarProducto(itemId) {
        if (confirm('¿Está seguro que desea eliminar este producto del ticket?')) {
            // Eliminar del array y actualizar la tabla
            ticketItems = ticketItems.filter(item => item.id !== itemId);
            actualizarTablaTicket({
                items: ticketItems
            });
        }
    }

    // Al cerrar la vista previa, redirigir a la lista de tickets
    document.addEventListener('DOMContentLoaded', () => {
        const previewModalEl = document.getElementById('ticketPreviewModal');
        previewModalEl.addEventListener('hidden.bs.modal', () => {
            window.location.href = '/GitHub/ticketscompra/tickets/lista';
        });
    });
</script>