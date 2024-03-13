<?php

use App\Models\Products;

$products           = new Products;
$lastAddedProducts  = $products->lastAdded(8);
$featuredProducts   = $products->featured(4);

// $lastAddedProducts = $products->getAll([
//     'ORDER_BY' => [
//         'created_at' => 'DESC'
//     ],
//     'LIMIT' => [
//         'limit' => 8
//     ],
// ]);


// $featuredProducts = $products->getAll([
//     'WHERE' => [
//         'featured' => 1,
//     ],
//     'LIMIT' => [
//         'limit' => 4
//     ],
// ]);

?>

<h1 class="visually-hidden">Página Principal</h1>

<!-- banner 1 -->
<section id="header" class="vh-100 carousel slide pb-5 mt-5" data-bs-ride="carousel" style="padding-top: 104px;">
    <div class="container h-100 d-flex align-items-center carousel-inner">
        <div class="text-center carousel-item active">
            <h2 class="text-capitalize text-white">Naturaleza en Casa</h2>
            <h3 class="text-uppercase py-2 fw-bold text-white h1">Nuevas Adquisiciones</h3>
            <a href="index.php?s=catalogo" class="btn mt-3 text-uppercase">¡Compra Ahora!</a>
        </div>
        <div class="text-center carousel-item">
            <h2 class="text-capitalize text-white">Elegancia Botánica</h2>
            <h3 class="text-uppercase py-2 fw-bold text-white h1">Novedades Frescas</h3>
            <a href="index.php?s=catalogo" class="btn mt-3 text-uppercase">¡Ver Productos!</a>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#header" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#header" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</section>

<!-- collection -->
<section id="collection" class="py-5">
    <div class="container">
        <div class="title text-center">
            <h2 class="position-relative d-inline-block">Nuevos Productos</h2>
        </div>

        <div class="row g-0">

            <div class="collection-list mt-4 row gx-0 gy-3 justify-content-between">

                <?php
                foreach ($lastAddedProducts as $product) :
                ?>


                    <div class="col-md-6 col-lg-4 col-xl-3 p-2">
                        <a href="index.php?s=detalle&id=<?= $product->getIdProduct() ?>" class="text-black text-decoration-none">
                            <div class="image-container collection-img position-relative">
                                <img src="<?= 'images/' . htmlspecialchars($product->getImage()) ?>" alt="<?= htmlspecialchars($product->getImageDescription()) ?>" class="w-100">
                                <span class="position-absolute bg-primary text-white d-flex align-items-center justify-content-center">sale</span>
                            </div>
                            <div class="text-center mt-3">
                                <div class="rating">
                                    <span class="text-primary"><i class="fas fa-star"></i></span>
                                    <span class="text-primary"><i class="fas fa-star"></i></span>
                                    <span class="text-primary"><i class="fas fa-star"></i></span>
                                    <span class="text-primary"><i class="fas fa-star"></i></span>
                                    <span class="text-primary"><i class="fas fa-star"></i></span>
                                </div>
                                <p class="text-capitalize my-1"><?= htmlspecialchars($product->getName()) ?></p>
                                <span class="fw-bold">$ <?= htmlspecialchars($product->getPrice()) ?></span>
                            </div>
                        </a>
                    </div>

                <?php
                endforeach;
                ?>

            </div>
        </div>
    </div>
</section>

<!-- special products -->
<section id="special" class="py-5">
    <div class="container">
        <div class="title text-center py-5">
            <h2 class="position-relative d-inline-block">Productos Destacados</h2>
        </div>

        <div class="special-list row g-0">

            <?php
            foreach ($featuredProducts as $product) :
            ?>


                <div class="col-md-6 col-lg-4 col-xl-3 p-2">
                    <div class="image-container special-img position-relative overflow-hidden">
                        <img src="<?= 'images/' . htmlspecialchars($product->getImage()) ?>" alt="<?= htmlspecialchars($product->getImageDescription()) ?>" class="w-100">
                        <span class="position-absolute d-flex align-items-center justify-content-center text-primary fs-4">
                            <i class="fas fa-heart"></i>
                        </span>
                    </div>
                    <div class="text-center">
                        <p class="text-capitalize mt-3 mb-1"><?= htmlspecialchars($product->getName()) ?></p>
                        <span class="fw-bold d-block">$ <?= htmlspecialchars($product->getPrice()) ?></span>
                        <a href="index.php?s=detalle&id=<?= $product->getIdProduct() ?>" class="btn btn-primary mt-3">¡Ver Producto!</a>
                    </div>
                </div>


            <?php
            endforeach;
            ?>

        </div>
    </div>
</section>

<!-- banner 2 -->
<section id="offers" class="py-5">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center text-center justify-content-lg-start text-lg-start">
            <div class="offers-content d-flex flex-column justify-content-center rounded-5 px-4">
                <span class="text-white">Descuento hasta 40%</span>
                <h2 class="mt-2 mb-4 text-white">¡Gran oferta de venta!</h2>
                <a href="index.php?s=catalogo" class="btn w-50">Comprar ahora</a>
            </div>
        </div>
    </div>
</section>

<!-- blogs -->
<section id="blogs" class="py-5">
    <div class="container">
        <div class="title text-center py-5">
            <h2 class="position-relative d-inline-block">Nuestros Últimos Blogs</h2>
        </div>

        <div class="row g-3">
            <div class="card border-0 col-md-6 col-lg-4 bg-transparent my-3">
                <img src="images/suculentas-blog.jpg" alt="">
                <div class="card-body px-0">
                    <h4 class="card-title">Secretos para Suculentas Felices</h4>
                    <p class="card-text mt-3 text-muted">Aprende a cultivar y cuidar suculentas para mantenerlas vibrantes y saludables. Descubre consejos esenciales para un hogar lleno de verdor y vitalidad.</p>
                    <p class="card-text">
                        <small class="text-muted">
                            <span class="fw-bold">Author: </span>Ignacio Barros
                        </small>
                    </p>
                    <a href="#" class="btn">Leer Más</a>
                </div>
            </div>

            <div class="card border-0 col-md-6 col-lg-4 bg-transparent my-3">
                <img src="images/macetas-blog.jpg" alt="">
                <div class="card-body px-0">
                    <h4 class="card-title">Macetas Transformadoras</h4>
                    <p class="card-text mt-3 text-muted">Inspírate con macetas de diseño que realzan la belleza de tus plantas. Descubre ideas creativas para decorar con estilo y naturaleza, creando espacios únicos y especiales.</p>
                    <p class="card-text">
                        <small class="text-muted">
                            <span class="fw-bold">Author: </span>Ignacio Barros
                        </small>
                    </p>
                    <a href="#" class="btn">Leer Más</a>
                </div>
            </div>

            <div class="card border-0 col-md-6 col-lg-4 bg-transparent my-3">
                <img src="images/plantas.blog.jpg" alt="">
                <div class="card-body px-0">
                    <h4 class="card-title">Explorando Plantas Extrañas</h4>
                    <p class="card-text mt-3 text-muted">Sumérgete en el mundo de las plantas exóticas. Descubre su diversidad única y conoce historias fascinantes detrás de estas maravillas botánicas que sorprenden en cada detalle.</p>
                    <p class="card-text">
                        <small class="text-muted">
                            <span class="fw-bold">Author: </span>Ignacio Barros
                        </small>
                    </p>
                    <a href="#" class="btn">Leer Más</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- about us -->
<section id="about" class="py-5">
    <div class="container">
        <div class="row gy-lg-5 align-items-center">
            <div class="col-lg-6 order-lg-1 text-center text-lg-start">
                <div class="title pt-3 pb-5">
                    <h2 class="position-relative d-inline-block ms-4">Sobre Nosotros</h2>
                </div>
                <p class="lead text-muted">Cultivando Belleza Natural</p>
                <p>En el corazón de nuestro ecommerce, se encuentra la pasión por las plantas y la conexión con la naturaleza. Nos enorgullece ofrecerte una variedad selecta de cactus, suculentas y más, cuidadosamente cultivados para aportar frescura y serenidad a tu vida. Explora nuestro mundo verde y descubre cómo cada planta lleva consigo una historia única que esperamos compartir contigo.</p>
            </div>
            <div class="col-lg-6 order-lg-0">
                <img src="images/jardineria-sobre-nosotros.jpg" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- newsletter -->
<section id="newsletter" class="py-5">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center">
            <div class="title text-center pt-3 pb-5">
                <h2 class="position-relative d-inline-block ms-4">¡Suscríbete a las Novedades!</h2>
            </div>

            <p class="text-center text-muted">"¡Únete a nuestra comunidad botánica y mantente al día con las últimas noticias, consejos de cuidado de plantas y ofertas especiales! Suscríbete a nuestro boletín y deja que la naturaleza florezca en tu bandeja de entrada."</p>
            <div class="input-group mb-3 mt-3">
                <input type="text" class="form-control" placeholder="Ingresa tu Email ...">
                <button class="btn btn-primary" type="submit">Suscribirme</button>
            </div>
        </div>
    </div>
</section>