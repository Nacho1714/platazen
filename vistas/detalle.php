<?php

use App\Models\Products;

$products = new Products();

$product = $products->getById(intval($_GET['id']));

if (!$product) header('Location: index.php?s=404');

$idProduct = $product->getIdProduct();
$idCategory = $product->getFkCategory(); 
$productsByCategory = $products->getByCategory($idCategory, $idProduct);

?>

<section class="container-details d-flex pb-5 mt-5 gap-3 container" style="padding-top: 104px">
	<div class="container-img h-100">
		<img class="w-100 h-100" src="<?= 'images/big-' . $product->getImage() ?>" alt="<?= htmlspecialchars($product->getImageDescription())?>" />
	</div>
	<div class="container-info-product h-100 d-flex flex-column">
		<div class="container-price pb-4 d-flex align-items-center justify-content-between">
			<span class="fs-4 fw-medium">$ <?= htmlspecialchars($product->getPrice())?></span>
		</div>

		<div class="py-4">
			<div class="form-group d-flex align-items-center mb-3 gap-3">
				<label class="fw-semibold" for="colour">Maceta</label>
				<select name="colour" id="colour">
					<option disabled selected value="">
						Escoge una opción
					</option>
					<option value="1">Cerámica</option>
					<option value="2">Moderna</option>
					<option value="3">Madera</option>
					<option value="4">Colgante Tejida</option>
				</select>
			</div>
			<div class="form-group d-flex align-items-center mb-3 gap-3">
				<label class="fw-semibold" for="size">Tamaño</label>
				<select name="size" id="size">
					<option disabled selected value="">
						Escoge una opción
					</option>
					<option value="40">Chico</option>
					<option value="42">Mediano</option>
					<option value="43">Grande</option>
				</select>
			</div>
			<button class="btn-clean">Limpiar</button>
		</div>

		<div class="container-add-cart d-flex gap-3 pb-4">
			<div class="position-relative">
				<input type="number" placeholder="1" value="1" min="1" class="input-quantity" />
				<div class="btn-increment-decrement">
					<i class="fa fa-chevron-up" id="increment"></i>
					<i class="fa fa-chevron-down" id="decrement"></i>
				</div>
			</div>
			<button class="btn">
				<i class="fa fa-plus"></i>
				Añadir al carrito
			</button>
		</div>

		<div class="h-100 d-flex flex-column justify-content-between">

			<div class="accordion accordion-flush" id="accordionFlush">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
							Descripción
						</button>
					</h2>
					<div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlush">
						<div class="accordion-body">
							<p><?= htmlspecialchars($product->getDescription())?></p>
						</div>
					</div>
				</div>
			</div>

			<div class="container-social d-flex justify-content-between align-items-center py-2">
				<span class="ps-3">Compartir</span>
				<div class="d-flex align-items-center gap-1">
					<a href="#" class="text-secondary"><i class="fa fa-envelope"></i></a>
					<a href="#" class="text-secondary"><i class="fab fa-facebook"></i></a>
					<a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
					<a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
					<a href="#" class="text-secondary"><i class="fab fa-pinterest"></i></a>
				</div>
			</div>

		</div>
	</div>
</section>

