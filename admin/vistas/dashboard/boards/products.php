<?php

use App\Models\Products;

$products = (new Products)->getAll();

?>

<table class="table table-striped table-hover">

    <thead>
        <tr>
            <th>Categoríaaa</th>
            <th>Estado</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Destacado</th>
            <th>Acción</th>
        </tr>
    </thead>

    <tbody>

        <?php
        foreach ($products as $product) :
        ?>

            <tr>
                <th><?= htmlspecialchars($product->getCategory()->getName()) ?></th>
                <th><?= htmlspecialchars($product->getState()->getName()) ?></th>
                <th><?= htmlspecialchars($product->getName()) ?></th>
                <th>$ <?= htmlspecialchars($product->getPrice()) ?></th>
                <th><?= htmlspecialchars($product->getStock()) ?></th>
                <th><?= htmlspecialchars($product->getFeatured()) ? 'Si' : 'No' ?></th>
                <th class="d-flex justify-content-between gap-1">

                    <a href="index.php?s=edit-product&idProduct=<?= $product->getIdProduct() ?>" class="edit rounded-5">
                        <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                    </a>
                    <button disabled class="delete btn btn-link rounded-5 p-0" data-bs-toggle="modal" data-bs-target="<?= '#delete-modal-' . $product->getIdProduct() ?>">
                        <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                    </button>

                    <div class="modal fade" tabindex="-1" id="<?= 'delete-modal-' . $product->getIdProduct() ?>" aria-labelledby="delete-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eliminar Producto</h5>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estas seguro/a que desea eliminar el producto <span class="fw-bold"><?= htmlspecialchars($product->getName()) ?></span>?</p>
                                </div>
                                <div class="modal-footer">
                                    <form class="form-action d-flex flex-row gap-2" action="actions/abm/delete-product.php" method="post">
                                        <input type="hidden" name="idProduct" value="<?= $product->getIdProduct() ?>">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </th>
            </tr>

        <?php
        endforeach;
        ?>

    </tbody>

</table>