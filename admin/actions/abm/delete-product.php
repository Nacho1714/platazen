<?php

use App\Auth\AuthManager;
use App\Models\Products;

require_once __DIR__ . '/../../../bootstrap/autoload.php';

// Autenticación
$authManager = (new AuthManager)->authFailureRedirect();

$idProduct = $_POST['idProduct'];

$product = (new Products)->getById($idProduct);

if (!$product) {
    $_SESSION['errors'] = 'El producto no existe';
    header('Location: ../../index.php?s=dashboard');
    exit;
}

try {
    $product->delete();
    unlink(__DIR__ . '/../../../images/big-' . $product->getImage());
    unlink(__DIR__ . '/../../../images/' . $product->getImage());
    $_SESSION['success'] = "El producto <b>" . $product->getName() . "</b> se eliminó correctamente";
    header('Location: ../../index.php?s=dashboard');
    exit;
} catch (\Throwable $th) {
    $_SESSION['errors'] = "El producto <b>" . $product->getName() . "</b> no se pudo eliminar";
    header('Location: ../../index.php?s=dashboard');
    exit;
}