<?php

use App\Auth\AuthManager;
use App\Models\Products;
use App\Validation\ValidateProduct;
use Intervention\Image\ImageManagerStatic as Image;

require_once __DIR__ . '/../../../bootstrap/autoload.php';

// Autenticación
$authManager = new AuthManager;
$authManager->authFailureRedirect();

// Captura de datos
$idProduct = $_POST['idProduct'];

$data = [
    'name'              => $_POST['name'],
    'category'          => $_POST['category'],
    'user'              => $authManager->getIdUserAuth(),
    'productState'      => $_POST['productState'],
    'description'       => $_POST['description'],
    'price'             => $_POST['price'],
    'stock'             => $_POST['stock'],
    'featured'          => isset($_POST['featured']) ? 1 : 0,
    'image'             => $_FILES['image'],
    'imageDescription'  => $_POST['image-description'],
];

// Verificación de existencia del producto
$product = (new Products)->getById($idProduct);

if (!$product) {
    $_SESSION['errors'] = 'El producto no existe';
    header('Location: ../../index.php?s=dashboard');
    exit;
}

// Validación de datos
$validate = new ValidateProduct([
    'name'              => [$data['name'], 'required|min:3|max:30'],
    'category'          => [$data['category'], 'required|numeric'],
    'productState'      => [$data['productState'], 'required|numeric'],
    'description'       => [$data['description'], 'required|min:10|max:500'],
    'price'             => [$data['price'], 'required|numeric|min:0|max:99999.99'],
    'stock'             => [$data['stock'], 'required|numeric|min:0|max:99999'],
    'imageDescription'  => [$data['imageDescription'], 'required|min:10|max:255'],
]);

if (!$validate->passes()) {
    $_SESSION['errorsForm'] = $validate->errors();
    $_SESSION['old_data'] = $data;
    header('Location: ../../index.php?s=edit-product&idProduct=' . $idProduct);
    exit;
}

// Procesamiento de la imagen
if (!empty($data['image']['name'])) {

    $imageName = date('YmdHis') . '-' . $data['image']['name'];
    
    unlink(__DIR__ . '/../../../images/big-' . $product->getImage());
    unlink(__DIR__ . '/../../../images/' . $product->getImage());
    
    $bigImage = Image::make($data['image']['tmp_name']);
    $bigImage->fit(648, 648)
    ->save(__DIR__ . '/../../../images/big-' . $imageName);
    
    $smallImage = Image::make($data['image']['tmp_name']);
    $smallImage->fit(308, 308)
    ->save(__DIR__ . '/../../../images/' . $imageName);
    
    $data['image'] = $imageName;
}

$data['image'] = $imageName ?? $product->getImage();

try {

    (new Products)->edit($idProduct, $data);
    $_SESSION['success'] = "El producto <b>" . $data['name'] . "</b> se actualizo correctamente";
    header('Location: ../../index.php?s=dashboard');
    exit;

} catch (Exception $e) {
    $_SESSION['errors'] = "Ocurrió un error al actualizar el producto. Por favor, intentá nuevamente";
    $_SESSION['old_data'] = $data;
    header('Location: ../../index.php?s=edit-product&idProduct=' . $idProduct);
    exit;
}

