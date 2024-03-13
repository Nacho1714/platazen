<?php

use App\Models\Categories;
use App\Models\ProductStates;

$errors = $_SESSION['errorsForm'] ?? [];
$oldData = $_SESSION['old_data'] ?? [];

$categories = (new Categories)->getAll();
$productStates = (new ProductStates)->getAll();

unset($_SESSION['errorsForm'], $_SESSION['old_data']);

?>

<div class="container my-5">

    <h2>Agregar Nuevo Producto</h2>

    <form action="actions/abm/create-product.php" method="post" enctype="multipart/form-data">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="name" class="form-label visually-hidden">Nombre del Producto</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    placeholder="Nombre del producto"
                    value="<?= $oldData['name'] ?? null ?>"
                    <?php if(isset($errors['name'])):?>
                        aria-descrcibedby="error-name"
                    <?php endif;?>
                >
                <?php
                if (isset($errors['name'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-name"><span class="visually-hidden">Error: </span><?= $errors['name'] ?></div>
                <?php
                endif;
                ?>
            </div>
            <div class="col-md-4">
                <label for="category" class="form-label visually-hidden">Categoría</label>
                <select
                    class="form-select"
                    id="category"
                    name="category"
                    <?php if(isset($errors['category'])):?>
                        aria-descrcibedby="error-category"
                    <?php endif;?>
                >
                    <option 
                        value="0" 
                        <?= empty($oldData['category']) ? 'selected' : null?> 
                    >
                        Selecciona una categoría
                    </option>

                    <?php
                    foreach ($categories as $category) :
                    ?>
                        <option 
                            value="<?= $category->getIdCategory()?>" 
                            <?= !empty($oldData['category']) && $oldData['category'] == $category->getIdCategory() ? 'selected' : null?>
                        >
                            <?= $category->getName()?>
                        </option>
                    <?php
                    endforeach;
                    ?>

                </select>
                <?php
                if (isset($errors['category'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-category"><span class="visually-hidden">Error: </span><?= $errors['category'] ?></div>
                <?php
                endif;
                ?>
            </div>
            <div class="col-md-4">
                <label for="category" class="form-label visually-hidden">Estado</label>
                <select
                    class="form-select"
                    id="productState"
                    name="productState"
                    <?php if(isset($errors['productState'])):?>
                        aria-descrcibedby="error-productState"
                    <?php endif;?>
                >
                    <option 
                        value="0" 
                        <?= empty($oldData['productState']) ? 'selected' : null?> 
                    >
                        Selecciona un estado
                    </option>

                    <?php
                    foreach ($productStates as $state) :
                    ?>
                        <option 
                            value="<?= $state->getIdProductState() ?>" 
                            <?= !empty($oldData['productState']) && $oldData['productState'] == $state->getIdProductState() ? 'selected' : null?>
                        >
                            <?= $state->getName() ?>
                        </option>
                    <?php
                    endforeach;
                    ?>

                </select>
                <?php
                if (isset($errors['productState'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-productState"><span class="visually-hidden">Error: </span><?= $errors['productState'] ?></div>
                <?php
                endif;
                ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label visually-hidden">Descripción</label>
            <textarea
                class="form-control"
                id="description"
                name="description"
                placeholder="Descripción del producto"
                rows="3"
                <?php if(isset($errors['description'])):?>
                    aria-descrcibedby="error-description"
                <?php endif;?>
            ><?= $oldData['description'] ?? null ?></textarea>
            <?php
            if (isset($errors['description'])) :
            ?>
                <div class="text-danger ps-2 fw-light" id="error-description"><span class="visually-hidden">Error: </span><?= $errors['description'] ?></div>
            <?php
            endif;
            ?>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="price" class="form-label visually-hidden">Precio</label>
                <input
                    type="number"
                    class="form-control"
                    id="price"
                    name="price"
                    placeholder="Precio"
                    step="0.01"
                    value="<?= $oldData['price'] ?? null ?>"
                    <?php if(isset($errors['price'])):?>
                        aria-descrcibedby="error-price"
                    <?php endif;?>
                >
                <?php
                if (isset($errors['price'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-price"><span class="visually-hidden">Error: </span><?= $errors['price'] ?></div>
                <?php
                endif;
                ?>
            </div>
            <div class="col-md-4 col-6">
                <label for="stock" class="form-label visually-hidden">Stock</label>
                <input
                    type="number"
                    class="form-control"
                    id="stock"
                    name="stock"
                    placeholder="Stock"
                    value="<?= $oldData['stock'] ?? null ?>"
                    <?php if(isset($errors['stock'])):?>
                        aria-descrcibedby="error-stock"
                    <?php endif;?>
                >
                <?php
                if (isset($errors['stock'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-stock"><span class="visually-hidden">Error: </span><?= $errors['stock'] ?></div>
                <?php
                endif;
                ?>
            </div>
            <div class="col-md-4 col-6 d-flex flex-column justify-content-center">
                <div class="form-check form-switch">
                    <label class="form-check-label" for="featured">Destacado</label>
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        id="featured"
                        name="featured"
                        <?= isset($oldData['featured']) && $oldData['featured'] == 1 ? 'checked' : null ?>
                    >
                </div>

            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="image" class="form-label visually-hidden">Cargar Imagen</label>
                <input
                    type="file"
                    class="form-control"
                    id="image"
                    name="image"
                    <?php if(isset($errors['image'])):?>
                        aria-descrcibedby="error-image"
                    <?php endif;?>
                    required
                >
                <?php
                if (isset($errors['image'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-image"><span class="visually-hidden">Error: </span><?= $errors['image'] ?></div>
                <?php
                endif;
                ?>
            </div>
            <div class="col-md-6">
                <label for="image-description" class="form-label visually-hidden">Descripción de la Imagen</label>
                <input
                    type="text"
                    class="form-control"
                    id="image-description"
                    name="image-description"
                    placeholder="Descripción de la imagen"
                    value="<?= $oldData['image-description'] ?? null ?>"
                    <?php if(isset($errors['imageDescription'])):?>
                        aria-descrcibedby="error-image-description"
                    <?php endif;?>
                >
                <?php
                if (isset($errors['imageDescription'])) :
                ?>
                    <div class="text-danger ps-2 fw-light" id="error-image-description"><span class="visually-hidden">Error: </span><?= $errors['imageDescription'] ?></div>
                <?php
                endif;
                ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Agregar Producto</button>

    </form>

</div>