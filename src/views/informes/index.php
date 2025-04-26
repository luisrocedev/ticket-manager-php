<?php if (!isset($ventas)) throw new Exception('No se han cargado los datos de ventas'); ?>

<div class="container-fluid">
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-chart-line"></i> <?php echo $titulo; ?></h2>
        </div>
        <div class="col-md-4">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                        value="<?php echo $fechaInicio; ?>">
                </div>
                <div class="col-md-6">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                        value="<?php echo $fechaFin; ?>">
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Resumen de ventas -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Resumen de Ventas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Ventas Totales</h6>
                                <h3 class="text-primary"><?php echo number_format($ventas['ventasTotales'], 2); ?> €</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>IVA Recaudado</h6>
                                <h3 class="text-info"><?php echo number_format($ventas['ivaTotalRecaudado'], 2); ?> €</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Número de Tickets</h6>
                                <h3 class="text-success"><?php echo $ventas['numeroTickets']; ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Ticket Promedio</h6>
                                <h3 class="text-warning"><?php echo number_format($ventas['ticketPromedioValor'], 2); ?> €</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos más vendidos -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Productos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Total Vendido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $producto): ?>
                                    <tr>
                                        <td><?php echo $producto['producto']->getNombre(); ?></td>
                                        <td><?php echo $producto['cantidad']; ?></td>
                                        <td><?php echo number_format($producto['total'], 2); ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métodos de pago -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Métodos de Pago</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th>Tickets</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($metodosPago as $metodo => $datos): ?>
                                    <tr>
                                        <td><?php echo ucfirst($metodo); ?></td>
                                        <td><?php echo $datos['cantidad']; ?></td>
                                        <td><?php echo number_format($datos['total'], 2); ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar el informe cuando cambien las fechas
        document.getElementById('fecha_inicio').addEventListener('change', actualizarInforme);
        document.getElementById('fecha_fin').addEventListener('change', actualizarInforme);
    });

    function actualizarInforme() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;

        if (fechaInicio && fechaFin) {
            window.location.href = `/GitHub/ticketscompra/informes?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }
    }
</script>