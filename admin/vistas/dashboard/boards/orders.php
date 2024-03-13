<?php

use App\Models\Order;

$orders = (new Order)->getAll();

?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Fec. Pedido</th>
            <th>Fec. Envio</th>
            <th>Dirección</th>
            <th>Met. de Pago</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>

        <?php
        foreach ($orders as $order) :
        ?>

            <tr>
                <th><?= htmlspecialchars($order->getFkUser()) ?></th>
                <th><?= htmlspecialchars($order->getFkOrderState()) ?></th>
                <th><?= htmlspecialchars($order->getOrderDate()) ?></th>
                <th><?= htmlspecialchars($order->getShippingDate()) ?></th>
                <th><?= htmlspecialchars($order->getShippingAddress()) ?></th>
                <th><?= htmlspecialchars($order->getPaymentMethod()) ?></th>
                <th>$ <?= htmlspecialchars($order->getTotal()) ?></th>
                <th class="d-flex justify-content-between gap-1">

                    <a href="index.php?s=dashboard&sb=orderDetail&idOrder=<?= $order->getIdOrder()?>" class="list rounded-5">
                        <i class="material-icons" data-toggle="tooltip" title="Edit">list_alt</i>
                    </a>

                </th>
            </tr>

        <?php
        endforeach;
        ?>

    </tbody>

</table>