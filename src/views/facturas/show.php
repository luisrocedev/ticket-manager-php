<?php
// View para mostrar detalle de factura
?>
<div class="container mt-4">
    <h2>Factura <?= htmlspecialchars($invoice['numero_factura'], ENT_QUOTES, 'UTF-8') ?></h2>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($invoice['fecha_emision'])) ?></p>
    <p><strong>Ticket:</strong> <?= htmlspecialchars($invoice['ticket']['numero_ticket'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php if (!empty($invoice['ticket']['cliente'])): ?>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($invoice['ticket']['cliente']['nombre'] . ' ' . $invoice['ticket']['cliente']['apellidos'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['producto'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= intval($item['cantidad']) ?></td>
                    <td><?= number_format($item['precio'], 2, ',', '.') ?> €</td>
                    <td><?= number_format($item['subtotal'], 2, ',', '.') ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6 offset-md-6">
            <p><strong>Subtotal:</strong> <?= number_format(array_reduce($invoice['items'], fn($s,$i)=>$s+$i['subtotal'],0), 2, ',', '.') ?> €</p>
            <p><strong>IVA:</strong> <?= number_format($invoice['ticket']['total'] - array_reduce($invoice['items'], fn($s,$i)=>$s+$i['subtotal'],0), 2, ',', '.') ?> €</p>
            <p><strong>Total:</strong> <?= number_format($invoice['ticket']['total'], 2, ',', '.') ?> €</p>
        </div>
    </div>

    <a href="/GitHub/ticketscompra/facturas/<?= $invoice['id'] ?>/pdf" class="btn btn-secondary mt-3">
        <i class="fas fa-file-pdf"></i> Descargar PDF
    </a>
    <a href="/GitHub/ticketscompra/facturas" class="btn btn-link mt-3">Volver a facturas</a>
</div>