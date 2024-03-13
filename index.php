<?php

require_once __DIR__ . '/bootstrap/autoload.php';

// Capturo la variable pasada por GET
// isset() es una función que verifica si una variable está definida (true) o no (false)
// $vista = isset($_GET['s']) ? $_GET['s'] : 'home'; // Con operador ternario
$vista = $_GET['s'] ?? 'home'; // Con operador de fusión de null (null coalesce). Si $_GET['s'] es "null" entonces $vista = 'home'

// Defino una "whitelist" de vistas válidas que permito que se carguen
$rutas = [
    'log-in' => [
        'title' => 'Iniciar sesión',
        'requiresAuth' => false,
    ],
    'log-up' => [
        'title' => 'Registrarse',
        'requiresAuth' => false,
    ],
    'home' => [
        'title' => 'Página de inicio',
        'requiresAuth' => true,
    ],
    'catalogo' => [
        'title' => 'Catálogo',
        'requiresAuth' => true,
    ],
    'contacto' => [
        'title' => 'Contacto',
        'requiresAuth' => true,
    ],
    'detalle' => [
        'title' => 'Detalle de producto',
        'requiresAuth' => true,
    ],
    'carrito' => [
        'title' => 'Carrito de compras',
        'requiresAuth' => true,
    ],
    'profile' => [
        'title' => 'Perfil',
        'requiresAuth' => true,
    ],
    '404' => [
        'title' => 'Página no encontrada'
    ],
];

// Verifico que la vista solicitada esté dentro de la "whitelist"
if (!isset($rutas[$vista])) {
    $vista = '404';
};

// Guardo en una variable los datos de la vista solicitada para facilitar su uso
$routeConfig = $rutas[$vista];
$routeConfig['scripts'][] = 'toast.js';

// flie_exists() es una función que verifica si un archivo/directorio existe (true) o no (false)
$filename = __DIR__ . '/vistas/' . $vista . '.php';
if (!file_exists($filename)) {
    $filename = __DIR__ . '/vistas/404.php';
}

// Autenticación
$authManager = new \App\Auth\AuthManager;
$requiresAuth = $routeConfig['requiresAuth'] ?? false;

if ($requiresAuth && !$authManager->checkAuth()) {
    $_SESSION['errors'] = 'Se requiere iniciar sesión para acceder a esta página';
    header('Location: index.php?s=log-in');
    exit;
}

// Mensajes de éxito y error
$successMessage = $_SESSION['success'] ?? null;
$errorsMessage = $_SESSION['errors'] ?? null;

unset($_SESSION['success'], $_SESSION['errors']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $routeConfig['title'] ?? '' ?> :: PlantaZen</title>

    <!-- favicon -->
    <link rel="icon" type="image/svg+xml" href="./images/cactus-icon.png" sizes="144x144" />

    <!-- fontawesome cdn -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

    <!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <!-- custom css -->
    <link rel="stylesheet" href="css/main.css">

</head>

<body>

    <?php
    if ($authManager->checkAuth()) :
    ?>

        <!-- navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">

            <div class="container">
                <a class="navbar-brand d-flex justify-content-between align-items-center order-lg-0" href="index.html">
                    <img src="images/cactus-icon.png" alt="site icon">
                    <span class="fw-lighter fs-2 ms-2">PlantaZen</span>
                </a>

                <div class="order-lg-2 nav-btns d-flex flex-row gap-3">
                    <a href="index.php?s=carrito" class="btn position-relative">
                        <span class="material-icons">shopping_cart</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                            5
                            <span class="visually-hidden">Number of shopping cart</span>
                        </span>
                    </a>
                    <a href="#" class="btn position-relative">
                        <span class="material-icons">favorite</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                            2
                            <span class="visually-hidden">Number of favorites</span>
                        </span>

                    </a>
                    <div class="dropdown">
  
                        <a href="#collapseProfile" class="btn position-relative" data-bs-toggle="dropdown" role="button" aria-expanded="false" aria-controls="collapseProfile" id="user">
                            <span class="material-icons">person</span>
                        </a>
                        <ul class="dropdown-menu">
                            
                            <li>
                                <a class="dropdown-item" href="index.php?s=profile">
                                    Perfil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">
                                Compras
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="actions/auth/log-out.php">
                                Salir
                                </a>
                            </li>

                        </ul>
                    </div>
   
                </div>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-lg-1" id="navMenu">
                    <ul class="navbar-nav mx-auto text-center">
                        <li class="nav-item px-2 py-2">
                            <a class="nav-link text-uppercase text-dark" href="index.php?s=home">Inicio</a>
                        </li>
                        <li class="nav-item px-2 py-2">
                            <a class="nav-link text-uppercase text-dark" href="index.php?s=catalogo">Catálogo</a>
                        </li>
                        <li class="nav-item px-2 py-2">
                            <a class="nav-link text-uppercase text-dark" href="index.php?s=contacto">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <?php
    endif;
    ?>

    <?php
    if ($successMessage || $errorsMessage) :
    ?>


        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast <?= 'text-bg-' . ($successMessage ? 'success' : 'danger') ?>" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="material-icons me-1"><?= $successMessage ? 'check_circle' : 'dangerous' ?></i>
                    <strong class="me-auto">¡Atenión!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body <?= 'text-bg-' . ($successMessage ? 'success' : 'danger') ?> fw-bold">
                    <?= $successMessage ? $successMessage : $errorsMessage ?>
                </div>
            </div>
        </div>

    <?php
    endif;
    ?>

    <?php
    require $filename;
    ?>

    <?php
    if ($authManager->checkAuth()) :
    ?>

        <!-- footer -->
        <footer class="bg-dark py-5 mt-5">
            <div class="container">
                <div class="row text-white g-4">
                    <div class="col-md-6 col-lg-3">
                        <a class="text-uppercase text-decoration-none brand text-white" href="index.php">PlantaZen</a>
                        <p class="text-secondary mt-3">"Explora la naturaleza en nuestro ecommerce. Plantas, cactus, suculentas y más para tu hogar. ¡Bienvenido a la frescura verde!"</p>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <h5 class="fw-light">Links</h5>
                        <ul class="list-unstyled">
                            <li class="my-3">
                                <a href="index.php?s=home" class="text-decoration-none text-secondary">
                                    <i class="fas fa-chevron-right me-1"></i> Inicio
                                </a>
                            </li>
                            <li class="my-3">
                                <a href="index.php?s=catalogo" class="text-decoration-none text-secondary">
                                    <i class="fas fa-chevron-right me-1"></i> Catálogo
                                </a>
                            </li>
                            <li class="my-3">
                                <a href="index.php?s=contacto" class="text-decoration-none text-secondary">
                                    <i class="fas fa-chevron-right me-1"></i> Contacto
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <h5 class="fw-light mb-3">Contáctanos</h5>
                        <div class="d-flex justify-content-start align-items-start my-2 text-secondary">
                            <span class="me-3">
                                <i class="fas fa-map-marked-alt"></i>
                            </span>
                            <span class="fw-light">
                                Av. Triunvirato 5958, Capital Federal, Buenos Aires, Argentina
                            </span>
                        </div>
                        <div class="d-flex justify-content-start align-items-start my-2 text-secondary">
                            <span class="me-3">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="fw-light">
                                plantazen.support@gmail.com
                            </span>
                        </div>
                        <div class="d-flex justify-content-start align-items-start my-2 text-secondary">
                            <span class="me-3">
                                <i class="fas fa-phone-alt"></i>
                            </span>
                            <span class="fw-light">
                                +54 9 11 9999-9999
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <h5 class="fw-light mb-3">Follow Us</h5>
                        <div>
                            <ul class="list-unstyled d-flex">
                                <li>
                                    <a href="#" class="text-decoration-none text-secondary fs-4 me-4">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-decoration-none text-secondary fs-4 me-4">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-decoration-none text-secondary fs-4 me-4">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-decoration-none text-secondary fs-4 me-4">
                                        <i class="fab fa-pinterest"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    <?php
    endif;
    ?>

    <!-- <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script> -->
    <!-- jquery -->
    <script src="js/jquery-3.6.0.js"></script>
    <!-- isotope js -->
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script src="js/script.js"></script>

    <?php
    foreach (($routeConfig['scripts'] ?? []) as $script) :
    ?>
        <script src="<?= "js/" . $script ?>"></script>
    <?php
    endforeach;
    ?>


</body>

</html>