<?php

$errors = $_SESSION['errorsForm'] ?? [];
$oldData = $_SESSION['old_data'] ?? [];

unset($_SESSION['errorsForm'], $_SESSION['old_data']);

?>

<section>
    <div class="row g-0">
        <div class="col-lg-7 img-1 d-none d-lg-block">
            <div class="carousel-item min-vh-100 active">
                <div class="carousel-caption d-none d-md-block">
                    <h5 class="font-weight-bold">La más potente del mercado</h5>
                    <a class="text-muted text-decoration-none">Visita nuestra tienda</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5 bg-dark d-flex flex-column align-items-end min-vh-100">
            <div class="px-lg-5 pt-lg-4 pb-lg-3 p-4 mb-3 w-100">
                <img src="../images/admin-img/login/Logo.svg" class="img-fluid" />
            </div>
            <div class="form-login align-self-center w-100 px-lg-5 py-lg-4 p-4 h-100 flex-column justify-content-lg-around d-flex">
                <h2 class="font-weight-bold mb-4 h1">Recuperar contraseña</h2>
                <form class="mb-5" action="actions/auth/password-recovery.php" method="post" novalidate>
                    <div class="mb-4">
                        <label for="email" class="form-label font-weight-bold">Email</label>
                        <input
                            name="email"
                            type="email"
                            class="form-control bg-dark-x border-0 text-white"
                            id="email"
                            value="<?= $oldData['email'] ?? '' ?>"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Enviar email de recuperación</button>
                </form>

                <?php
                if (!empty($errors)) :
                ?>

                    <div class="alert alert-danger" role="alert" id="error-alert">
                        <?= $errors['email'] ?>
                    </div>

                <?php
                endif;
                ?>

                <div class="d-flex flex-column">
                    <p class="font-weight-bold text-center text-white">O inicia sesión con</p>
                    <div class="d-flex flex-row  justify-content-around">
                        <button type="button" class="btn btn-outline-light flex-grow-1 mr-2 google"><i class="fab fa-google lead mr-2"></i> Google</button>
                        <button type="button" class="btn btn-outline-light flex-grow-1 ml-2 facebook"><i class="fab fa-facebook-f lead mr-2"></i> Facebook</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>