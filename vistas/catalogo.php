<?php

use App\Models\Categories;
use App\Models\Products;

$categories = (new Categories)->getAll();

$productsFactory    = new Products;
$products           = $productsFactory->published(2);
$paginator          = $productsFactory->getPaginator();

// echo "<pre>";
// print_r($paginator);
// echo "</pre>";
?>

<section id="collection" class="py-5">
    <div class="container">
        <div class="title text-center">
            <h2 class="position-relative d-inline-block">Nuestros Productos</h2>
        </div>

        <div class="row g-0">
            <div class="d-flex flex-wrap justify-content-center mt-5 filter-button-group">

                <button type="button" class="btn m-2 text-dark active-filter-btn" data-filter="*">Todo</button>

                <?php 
                    foreach ($categories as $category) :
                ?>

                    <button type="button" class="btn m-2 text-dark" data-filter=".<?= htmlspecialchars($category->getName())?>"><?= htmlspecialchars($category->getName())?></button>

                <?php
                    endforeach;
                ?>

            </div>

            <div class="collection-list mt-4 row gx-0 gy-3 justify-content-between">

                <?php
                    foreach ($products as $product) :
                ?>

                    <div class="col-md-6 col-lg-4 col-xl-3 p-2 <?= htmlspecialchars($product->getCategory()->getName())?>">
                        <div class="image-container special-img position-relative overflow-hidden">
                            <img src="<?= 'images/' . htmlspecialchars($product->getImage()) ?>" alt="<?= htmlspecialchars($product->getDescription())?>" class="w-100">
                            <span
                                class="position-absolute d-flex align-items-center justify-content-center text-primary fs-4">
                                <i class="fas fa-heart"></i>
                            </span>
                        </div>
                        <div class="d-flex flex flex-row justify-content-between align-items-center mt-3">
                            <div>
                                <div>
                                    <span class="fw-bold d-block fs-4"><?= htmlspecialchars($product->getCategory()->getName())?></span>
                                </div>
                                <div>
                                    <div class="rating">
                                        <span class="text-primary"><i class="fas fa-star"></i></span>
                                        <span class="text-primary"><i class="fas fa-star"></i></span>
                                        <span class="text-primary"><i class="fas fa-star"></i></span>
                                        <span class="text-primary"><i class="fas fa-star"></i></span>
                                        <span class="text-primary"><i class="fas fa-star"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pe-3"> 
                                <span class="fw-bold d-block fs-2 text-nowrap">$ <?= htmlspecialchars($product->getPrice())?></span>
                            </div>
                        </div>
                        <p class="text-capitalize mb-0 text-nowrap"><?= htmlspecialchars($product->getName())?></p>
                        <a href="index.php?s=detalle&id=<?= $product->getIdProduct()?>" class="btn btn-primary mt-3 w-100">¡Ver Producto!</a>
                    </div>

                <?php
                    endforeach;
                ?>

            </div>

        </div>
    </div>
</section>
