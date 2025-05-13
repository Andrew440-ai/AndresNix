<?php  
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('content-type: application/json; charset=utf-8');

require 'productsModel.php';
$productModel = new productModel();

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $respuesta = (!isset($_GET['product_id'])) ? $productModel->getProducts() : $productModel->getProducts($_GET['product_id']);
        echo json_encode($respuesta);
    break;

    case 'POST':
        $_POST = json_decode(file_get_contents('php://input', true));
        if(!isset($_POST->name) || empty(trim($_POST->name)) || strlen($_POST->name) > 50){
            $respuesta = ['error','The product name must not be empty and must not exceed 50 characters.'];
        }
        else if(!isset($_POST->description) || empty(trim($_POST->description)) || strlen($_POST->description) > 350){
            $respuesta = ['error','The product description must not be empty and must not exceed 350 characters.'];
        }
        else if(!isset($_POST->category) || empty(trim($_POST->category)) || strlen($_POST->category) > 150){
            $respuesta = ['error','The product category must not be empty and must not exceed 150 characters.'];
        }
        else if(!isset($_POST->expiration_date) || empty(trim($_POST->expiration_date))){
            $respuesta = ['error','The product expiration date must not be empty.'];
        }
        else if(!isset($_POST->price) || !is_numeric($_POST->price)){
            $respuesta = ['error','The product price must be a numeric value.'];
        }
        else {
            $respuesta = $productModel->saveProduct($_POST->name, $_POST->description, $_POST->category, $_POST->expiration_date, $_POST->price);
        }
        echo json_encode($respuesta);
    break;

    case 'PUT':
        $_PUT = json_decode(file_get_contents('php://input', true));
        if(!isset($_PUT->product_id)){
            $respuesta = ['error','The product ID must not be empty.'];
        }
        else if(!isset($_PUT->name) || empty(trim($_PUT->name)) || strlen($_PUT->name) > 50){
            $respuesta = ['error','The product name must not be empty and must not exceed 50 characters.'];
        }
        else if(!isset($_PUT->description) || empty(trim($_PUT->description)) || strlen($_PUT->description) > 350){
            $respuesta = ['error','The product description must not be empty and must not exceed 350 characters.'];
        }
        else if(!isset($_PUT->category) || empty(trim($_PUT->category)) || strlen($_PUT->category) > 150){
            $respuesta = ['error','The product category must not be empty and must not exceed 150 characters.'];
        }
        else if(!isset($_PUT->expiration_date) || empty(trim($_PUT->expiration_date))){
            $respuesta = ['error','The product expiration date must not be empty.'];
        }
        else if(!isset($_PUT->price) || !is_numeric($_PUT->price)){
            $respuesta = ['error','The product price must be a numeric value.'];
        }
        else {
            $respuesta = $productModel->updateProduct($_PUT->product_id, $_PUT->name, $_PUT->description, $_PUT->category, $_PUT->expiration_date, $_PUT->price);
        }
        echo json_encode($respuesta);
    break;

    case 'DELETE':
        $_DELETE = json_decode(file_get_contents('php://input', true));
        if(!isset($_DELETE->product_id)){
            $respuesta = ['error','The product ID must not be empty.'];
        }
        else {
            $respuesta = $productModel->deleteProduct($_DELETE->product_id);
        }
        echo json_encode($respuesta);
    break;
}
