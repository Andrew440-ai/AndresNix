<?php  
class productModel {
    public $conexion;

    public function __construct() {
        $this->conexion = new mysqli('localhost', 'root', '', 'distrimedicasd');
        mysqli_set_charset($this->conexion, 'utf8');
    }

    public function getProducts($product_id = null) {
        $where = ($product_id == null) ? "" : " WHERE product_id='$product_id'";
        $products = [];
        $sql = "SELECT * FROM product " . $where;
        $registros = mysqli_query($this->conexion, $sql);
        while ($row = mysqli_fetch_assoc($registros)) {
            array_push($products, $row);
        }
        return $products;
    }

    public function saveProduct($name, $description, $category, $expiration_date, $price) {
        $valida = $this->validateProduct($name, $description, $category, $expiration_date, $price);
        $resultado = ['error', 'A product with the same details already exists'];
        if (count($valida) == 0) {
            $sql = "INSERT INTO product(name, description, category, expiration_date, price) 
                    VALUES('$name', '$description', '$category', '$expiration_date', '$price')";
            mysqli_query($this->conexion, $sql);
            $resultado = ['success', 'Product saved successfully'];
        }
        return $resultado;
    }

    public function updateProduct($product_id, $name, $description, $category, $expiration_date, $price) {
        $existe = $this->getProducts($product_id);
        $resultado = ['error', 'No product found with ID ' . $product_id];
        if (count($existe) > 0) {
            $valida = $this->validateProduct($name, $description, $category, $expiration_date, $price);
            $resultado = ['error', 'A product with the same details already exists'];
            if (count($valida) == 0) {
                $sql = "UPDATE product SET name='$name', description='$description', category='$category', 
                        expiration_date='$expiration_date', price='$price' WHERE product_id='$product_id'";
                mysqli_query($this->conexion, $sql);
                $resultado = ['success', 'Product updated successfully'];
            }
        }
        return $resultado;
    }

    public function deleteProduct($product_id) {
        $valida = $this->getProducts($product_id);
        $resultado = ['error', 'No product found with ID ' . $product_id];
        if (count($valida) > 0) {
            $sql = "DELETE FROM product WHERE product_id='$product_id'";
            mysqli_query($this->conexion, $sql);
            $resultado = ['success', 'Product deleted successfully'];
        }
        return $resultado;
    }

    public function validateProduct($name, $description, $category, $expiration_date, $price) {
        $products = [];
        $sql = "SELECT * FROM product WHERE name='$name' AND description='$description' 
                AND category='$category' AND expiration_date='$expiration_date' AND price='$price'";
        $registros = mysqli_query($this->conexion, $sql);
        while ($row = mysqli_fetch_assoc($registros)) {
            array_push($products, $row);
        }
        return $products;
    }
}
