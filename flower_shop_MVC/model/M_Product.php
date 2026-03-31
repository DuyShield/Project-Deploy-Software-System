<?php
require_once "config/database.php";

class M_Product
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllProducts()
    {
        $sql = "SELECT p.*, c.name_category FROM products p LEFT JOIN categories c ON p.id_category = c.id_category";
        return $this->db->select($sql);
    }

    public function getProductById($id)
    {

        $sql = "SELECT * FROM products WHERE id_product = ?";
        $result = $this->db->select($sql, "i", [$id]);
        return $result[0] ?? null;
    }
    public function searchProducts($keyword)
    {
        $sql = "SELECT * FROM products Where name_product LIKE ?";
        $keyword = "%$keyword%";
        $result = $this->db->select($sql, "s", [$keyword]);
        return $result;
    }
    public function addProduct($name, $description, $price, $image, $id_category)
    {
        $sql = "INSERT INTO products (name_product, description_product, price_product, image, id_category)
                VALUES (?,?,?,?,?)";
        return $this->db->execute($sql, "ssisi", [$name, $description, $price, $image, $id_category]);
    }
    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE id_product = ?";
        return $this->db->execute($sql, "i", [$id]);
    }
    public function updateProduct($id, $name, $desc, $price, $image, $category)
    {
        $sql = "UPDATE products 
            SET name_product=?, description_product=?, price_product=?, image=?, id_category=? 
            WHERE id_product=?";
        return $this->db->execute($sql, "ssdsii", [$name, $desc, $price, $image, $category, $id]);
    }
    public function updateProductNoImage($id, $name, $desc, $price, $category)
    {
        $sql = "UPDATE products
            SET name_product=?, description_product=?, price_product=?, id_category=? 
            WHERE id_product=?";
        return $this->db->execute($sql, "ssdii", [$name, $desc, $price, $category, $id]);
    }
    public function getProductsByCategory($id_category)
    {
        $sql = "SELECT * FROM products WHERE id_category = ?";
        return $this->db->select($sql, "i", [$id_category]);
    }
}