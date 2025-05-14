<?php
class productModel {
    public $conexion;

    public function __construct() {
        $host = 'dpg-d0iemm7diees738h6990-a';       // Reemplaza esto con tu Host real
        $db   = 'distrimedicasd';           // Nombre de la base de datos
        $user = 'admin';               // Usuario de la DB
        $pass = 'gZ8mo0XmBU6X4E2OmFFm7m8FsFBZlnyC';            // Contraseña segura
        $port = '5432';                     // Puerto (por defecto 5432)

        try {
            $this->conexion = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getProducts($product_id = null) {
        $sql = "SELECT * FROM product";
        if ($product_id !== null) {
            $sql .= " WHERE product_id = :product_id";
        }

        $stmt = $this->conexion->prepare($sql);
        if ($product_id !== null) {
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveProduct($name, $description, $category, $expiration_date, $price) {
        $valida = $this->validateProduct($name, $description, $category, $expiration_date, $price);
        if (count($valida) > 0) {
            return ['error', 'A product with the same details already exists'];
        }

        $sql = "INSERT INTO product (name, description, category, expiration_date, price)
                VALUES (:name, :description, :category, :expiration_date, :price)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':category' => $category,
            ':expiration_date' => $expiration_date,
            ':price' => $price
        ]);
        return ['success', 'Product saved successfully'];
    }

    public function updateProduct($product_id, $name, $description, $category, $expiration_date, $price) {
        $existe = $this->getProducts($product_id);
        if (count($existe) == 0) {
            return ['error', 'No product found with ID ' . $product_id];
        }

        $valida = $this->validateProduct($name, $description, $category, $expiration_date, $price);
        if (count($valida) > 0) {
            return ['error', 'A product with the same details already exists'];
        }

        $sql = "UPDATE product SET name = :name, description = :description, category = :category,
                expiration_date = :expiration_date, price = :price WHERE product_id = :product_id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':product_id' => $product_id,
            ':name' => $name,
            ':description' => $description,
            ':category' => $category,
            ':expiration_date' => $expiration_date,
            ':price' => $price
        ]);
        return ['success', 'Product updated successfully'];
    }

    public function deleteProduct($product_id) {
        $existe = $this->getProducts($product_id);
        if (count($existe) == 0) {
            return ['error', 'No product found with ID ' . $product_id];
        }

        $sql = "DELETE FROM product WHERE product_id = :product_id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':product_id' => $product_id]);
        return ['success', 'Product deleted successfully'];
    }

    public function validateProduct($name, $description, $category, $expiration_date, $price) {
        $sql = "SELECT * FROM product WHERE name = :name AND description = :description AND category = :category
                AND expiration_date = :expiration_date AND price = :price";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':category' => $category,
            ':expiration_date' => $expiration_date,
            ':price' => $price
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
