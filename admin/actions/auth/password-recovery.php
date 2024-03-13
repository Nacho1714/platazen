<?php

use App\Auth\RecoverPassword;
use App\Models\User;
use App\Validation\ValidateUser;

require_once __DIR__ . '/../../../bootstrap/autoload.php';

$email = $_POST['email'];

// Validación de datos
$validate = new ValidateUser([
    'email' => [$email, 'required|email'],
]);

if (!$validate->passes()) {
    $_SESSION['errorsForm'] = $validate->errors();
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-recovery');
    exit;
}

// Verifico si el usuario ya existe en la base de datos
$user = (new User)->getByEmail($email);

if (!$user) {
    $_SESSION['errorsForm'] = ['email' => 'El email ingresado no existe en nuestra base de datos'];
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-recovery');
    exit;
}

// Manejo del envío del email
$recoverUser = new RecoverPassword($user);

try {

    $recoverUser->sendRecoveryEmail();
    $_SESSION['success'] = "Se envió un email a <b>" . $email . "</b> con las instrucciones para recuperar tu contraseña";
    header('Location: ../../index.php?s=password-recovery');
    exit;

} catch (Exception $e) {

    $_SESSION['errors'] = "Ocurrió un error al enviar el email. Por favor, intentá nuevamente";
    $_SESSION['old_data'] = $email;
    header('Location: ../../index.php?s=password-recovery');
    exit;

}