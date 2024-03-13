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
    header('Location: ../../index.php?s=create-product');
    exit;
}

// Procesamiento de la imagen
$imageName = date('YmdHis') . '-' . $data['image']['name'];

$bigImage = Image::make($data['image']['tmp_name']);
$bigImage->fit(648, 648)
->save(__DIR__ . '/../../../images/big-' . $imageName);

$smallImage = Image::make($data['image']['tmp_name']);
$smallImage->fit(308, 308)
->save(__DIR__ . '/../../../images/' . $imageName);

try {

    (new Products)->create($data);
    $_SESSION['success'] = "El producto <b>" . $data['name'] . "</b> se creó correctamente";
    header('Location: ../../index.php?s=dashboard');
    exit;

} catch (Exception $e) {

    $_SESSION['errors'] = "Ocurrió un error al crear el producto. Por favor, intentá nuevamente";
    $_SESSION['old_data'] = $data;
    header('Location: ../../index.php?s=create-product');
    exit;

}