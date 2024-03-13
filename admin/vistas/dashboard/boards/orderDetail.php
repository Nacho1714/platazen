<?php

use App\Models\Order;

$order = new Order;

$order->setIdOrder($_GET['idOrder']);
$order->chargeProducts();
$products = $order->getProducts();

?>

<table class="table table-striped table-hover">

    <thead>
        <tr>
            <th>Categoríaaa</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
    </thead>

    <tbody>

        <?php
        foreach ($products as $product) :
        ?>

            <tr>
                <th><?= htmlspecialchars($product->getCategory()->getName()) ?></th>
                <th><?= htmlspecialchars($product->getName()) ?></th>
                <th>$ <?= htmlspecialchars($product->getPrice()) ?></th>
                <th><?= htmlspecialchars($product->getCant()) ?></th>
                <th>$ <?= htmlspecialchars($product->getSubtotal())?></th>

            </tr>

        <?php
        endforeach;
        ?>

    </tbody>

</table>