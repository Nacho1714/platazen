<?php

use App\Auth\AuthManager;
use App\Models\User;
use App\Validation\ValidateUser;

require_once __DIR__ . '/../../bootstrap/autoload.php';

$data = [
    'email' => $_POST['email'],
    'password' => $_POST['password'],
];

// Validación de datos
$validate = new ValidateUser([
    'email' => [$data['email'], 'required|email'],
]);

if (!$validate->passes()) {
    $_SESSION['errorsForm'] = $validate->errors();
    $_SESSION['old_data'] = $data;
    header('Location: ../../index.php?s=log-in');
    exit;
}

$authManager = new AuthManager;

if (!$authManager->login($data)) {
    
    $_SESSION['errorsForm'] = ['email' => 'Credenciales incorrectas'];
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../index.php?s=log-in');
    exit;
} 

$_SESSION['success'] = 'Sesión iniciada correctamente';
header('Location: ../../index.php?s=home');
exit;

    