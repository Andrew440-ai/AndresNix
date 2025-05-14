<?php
require_once 'productsModel.php';

$model = new productModel();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        $product_id = $_GET['product_id'] ?? null;
        $products = $model->getProducts($product_id);
        echo json_encode($products);
        break;

    case 'save':
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $category = $data['category'] ?? '';
        $expiration_date = $data['expiration_date'] ?? '';
        $price = $data['price'] ?? '';

        $response = $model->saveProduct($name, $description, $category, $expiration_date, $price);
        echo json_encode($response);
        break;

    case 'update':
        $data = json_decode(file_get_contents("php://input"), true);
        $product_id = $data['product_id'] ?? null;
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $category = $data['category'] ?? '';
        $expiration_date = $data['expiration_date'] ?? '';
        $price = $data['price'] ?? '';

        if ($product_id) {
            $response = $model->updateProduct($product_id, $name, $description, $category, $expiration_date, $price);
        } else {
            $response = ['error', 'Product ID is required for update'];
        }
        echo json_encode($response);
        break;

    case 'delete':
        $data = json_decode(file_get_contents("php://input"), true);
        $product_id = $data['product_id'] ?? null;

        if ($product_id) {
            $response = $model->deleteProduct($product_id);
        } else {
            $response = ['error', 'Product ID is required for deletion'];
        }
        echo json_encode($response);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
