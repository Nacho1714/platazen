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
                <h2 class="font-weight-bold mb-4 h1">Actualizar contraseña</h2>
                <div class="alert alert-info" role="alert" id="error-alert">
                    Por favor, elija una contraseña que sea segura. Debe tener al menos 8 caracteres, incluyendo al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.
                </div>
                <form class="mb-5" action="actions/auth/password-update.php" method="post" novalidate>

                    <!-- 
                        Agrego como campos ocultos el token y el id del usuario.
                        El id para poder saber a qué usuario le tenemos que cambiar la contraseña.
                        El token para asegurarme de que este acceso corresponda con un email que se envió desde el sistema.
                        
                    -->

                    <input 
                        type="hidden" 
                        name="idUser"
                        value="<?= $_GET['idUser'] ?>" 
                    >
                    
                    <input 
                        type="hidden" 
                        name="token"
                        value="<?= $_GET['token'] ?>"
                    >

                    <div class="mb-4">
                        <label for="password" class="form-label font-weight-bold">Contraseña</label>
                        <div class="input-group">
                            <input
                                name="password"
                                type="password"
                                class="form-control bg-dark-x border-0 mb-2 text-white"
                                id="password"
                                value="<?= $oldData['password'] ?? '' ?>"
                                data-toggle-password
                            >
                            <div class="input-group-append mb-2" data-toggle-button style="cursor: pointer;">
                                <span class="input-group-text h-100">
                                    <i class="material-icons">remove_red_eye</i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label font-weight-bold">Confirmar contraseña</label>
                        <div class="input-group">
                            <input
                                name="password-confirm"
                                type="password"
                                class="form-control bg-dark-x border-0 mb-2 text-white"
                                id="password-confirm"
                                value="<?= $oldData['password-confirm'] ?? '' ?>"
                                data-toggle-password
                            >
                            <div class="input-group-append mb-2" data-toggle-button style="cursor: pointer;">
                                <span class="input-group-text h-100">
                                    <i class="material-icons">remove_red_eye</i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                </form>

                <?php
                if (!empty($errors)) :
                ?>

                    <div class="alert alert-danger" role="alert" id="error-alert">
                        <?= $errors['password'] ?? $errors['password-confirm'] ?>
                    </div>

                <?php
                endif;
                ?>

            </div>
        </div>
    </div>
</section>

