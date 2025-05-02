<?php
// Plantilla HTML para generar PDF de la factura
// Se espera un array $data obtenido de Factura::toArray()
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura <?= htmlspecialchars($data['numeroFactura'], ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>Factura <?= htmlspecialchars($data['numeroFactura'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p><strong>Fecha de emisión:</strong> <?= date('d/m/Y', strtotime($data['fechaEmision'])) ?></p>
    <p><strong>Ticket:</strong> <?= htmlspecialchars($data['ticket']['numeroTicket'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php if (!empty($data['ticket']['cliente'])): ?>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($data['ticket']['cliente']['nombre'] . ' ' . $data['ticket']['cliente']['apellidos'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['ticket']['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['producto']['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= intval($item['cantidad']) ?></td>
                    <td class="text-right"><?= number_format($item['precioUnitario'], 2, ',', '.') ?> €</td>
                    <td class="text-right"><?= number_format($item['subtotal'], 2, ',', '.') ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $subtotal = array_reduce(
        $data['ticket']['items'],
        fn($sum, $i) => $sum + $i['subtotal'],
        0
    );
    $iva = array_reduce(
        $data['ticket']['items'],
        fn($sum, $i) => $sum + $i['ivaImporte'],
        0
    );
    $total = $subtotal + $iva;
    ?>
    <div class="mt-20">
        <table style="width: 40%; float: right; border: none;">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right"><?= number_format($subtotal, 2, ',', '.') ?> €</td>
            </tr>
            <tr>
                <td><strong>IVA:</strong></td>
                <td class="text-right"><?= number_format($iva, 2, ',', '.') ?> €</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-right"><?= number_format($total, 2, ',', '.') ?> €</td>
            </tr>
        </table>
    </div>

    <div style="clear: both; margin-top: 40px; text-align: center;">
        <p>¡Gracias por su compra!</p>
    </div>
</body>

</html>