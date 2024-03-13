<?php

use App\Models\User;
use App\Validation\ValidateUser;

require_once __DIR__ . '/../../../bootstrap/autoload.php';

// Capturo los datos
$data = [
    'idUser' => $_POST['idUser'],
    'token' => $_POST['token'],
    'password' => $_POST['password'],
    'password-confirm' => $_POST['password-confirm'],
];

// Validación de datos
$validate = new ValidateUser([
    'password' => [$data['password'], 'required|password'],
    'password-confirm' => [$data['password-confirm'], 'required|passwordConfirm:password']
]);

if (!$validate->passes()) {
    $_SESSION['errorsForm'] = $validate->errors();
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-update&token=' . $data['token'] . '&idUser=' . $data['idUser']);
    exit;
}

// Verifico si el usuario ya existe en la base de datos
$user = (new User)->getById($data['idUser']);

if (!$user) {
    $_SESSION['errors'] = 'El usuario no existe en nuestra base de datos';
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-recovery');
    exit;
}

// Verifico si el token es válido
$recoverPassword = new \App\Auth\RecoverPassword($user);
$recoverPassword->setToken($data['token']);

if (!$recoverPassword->isValidToken()) {
    $_SESSION['errors'] = 'El link no es válido, por favor, solicite un nuevo cambio de contraseña';
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-recovery');
    exit;
}

if (!$recoverPassword->isValidExpiration()) {
    $_SESSION['errors'] = 'El token ha expirado, por favor, solicite un nuevo cambio de contraseña';
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-recovery');
    exit;
}

try {
    $recoverPassword->updatePassword($data['password']);
    $_SESSION['success'] = 'La contraseña se actualizó correctamente';
    header('Location: ../../index.php?s=log-in');
    exit;
} catch (\Throwable $th) {
    $_SESSION['errorsForm'] = ['password' => 'Hubo un error al actualizar la contraseña, por favor, intente nuevamente'];
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=password-update&token=' . $data['token'] . '&idUser=' . $data['idUser']);
    exit;
}