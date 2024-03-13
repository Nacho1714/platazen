<?php

$board = $_GET['sb'] ?? 'products';

$boards = [
	'products' => [
		'title' => 'Productos',
		'buttonAdd' => true,
	],
	'orders' => [
		'title' => 'Pedidos',
	],
	'orderDetail' => [
		'title' => 'Detalle de pedido',
	],
];

// Si el board no existe, se usa el board de productos
if (!isset($boards[$board])) {
	$board = 'products';
};

// Obtener la configuración del board
$boardConfig = $boards[$board];

// Verificar si existe el archivo del board
$filename = __DIR__ . '/boards/'. $board . '.php';
if (!file_exists($filename)) {
	$filename = __DIR__ . '/boards/products.php';
}

?>

<section class="main-content">
	<div class="row">
		<div class="col-md-12">
			<div class="table-wrapper">

				<div class="table-title">
					<div class="row">
						<div class="col-sm-6 p-0 flex justify-content-lg-start justify-content-center">
							<h2 class="ml-lg-2"><?= $boardConfig['title']?></h2>
						</div>
						<?php
						if (isset($boardConfig['buttonAdd'])) :
						?>
							<div class="col-sm-6 p-0 flex justify-content-lg-end justify-content-center">
								<a href="index.php?s=create-product" class="btn btn-success">
									<i class="material-icons">&#xE147;</i>
									<span>Agregar <?= $boardConfig['title'] ?></span>
								</a>
							</div>
						<?php
						endif;
						?>
					</div>
				</div>

				<?php
					require $filename;
				?>

			</div>

		</div>

	</div>

</section>