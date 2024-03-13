<?php

use App\Models\User;
use App\Validation\ValidateUser;

require_once __DIR__ . '/../../bootstrap/autoload.php';

$data = [
    'email'              => $_POST['email'],
    'password'           => $_POST['password'],
    'fkRol'              => 2,
];

// Validación de datos
// TODO: validar que la contraseña coincida con la confirmación de contraseña
$validate = new ValidateUser([
    'email' => [$data['email'], 'required|email'],
]);

if (!$validate->passes()) {
    $_SESSION['errorsForm'] = $validate->errors();
    $_SESSION['old_data'] = $data;
    header('Location: ../../index.php?s=log-up');
    exit;
}

$user = new User;

try {

    $user->create($data);
    $_SESSION['success'] = 'Usuario creado correctamente';
    header('Location: ../../index.php?s=log-in');
    exit;
    
} catch (Exception $e) {
    $_SESSION['errorsForm'] = ['email' => 'El email ya está en uso'];
    $_SESSION['old_data'] = $data;
    header('Location: ../../index.php?s=log-up');
    exit;
}

