<?php

use App\Auth\AuthManager;

require_once __DIR__ . '/../../bootstrap/autoload.php';

$authManager = new AuthManager;

$authManager->logout();

$_SESSION['success'] = 'Sesión cerrada correctamente';
header('Location: ../../index.php?s=log-in');
exit;