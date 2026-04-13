<?php
require_once "config/database.php";

class M_Product
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    //Lấy tất cả sản phẩm
    public function getAllProducts()
    {
        $sql = "SELECT p.*, c.name_category FROM products p LEFT JOIN categories c ON p.id_category = c.id_category";
        return $this->db->select($sql);
    }
    //Lấy sản phẩm theo ID
    public function getProductById($id)
    {

        $sql = "SELECT * FROM products WHERE id_product = ?";
        $result = $this->db->select($sql, "i", [$id]);
        return $result[0] ?? null;
    }
    //Tìm kiếm sản phẩm theo tên
    public function searchProducts($keyword)
    {
        $sql = "SELECT p.*, c.name_category 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_category 
            WHERE p.name_product LIKE ?";
        $keyword = "%$keyword%";
        $result = $this->db->select($sql, "s", [$keyword]);
        return $result;
    }
    //Thêm sản phẩm mới
    public function addProduct($name, $description, $price, $image, $id_category)
    {
        $sql = "INSERT INTO products (name_product, description_product, price_product, image, id_category)
                VALUES (?,?,?,?,?)";
        return $this->db->execute($sql, "ssisi", [$name, $description, $price, $image, $id_category]);
    }
    //Xóa sản phẩm
    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE id_product = ?";
        return $this->db->execute($sql, "i", [$id]);
    }
    //Cập nhật sản phẩm
    public function updateProduct($id, $name, $desc, $price, $image, $category)
    {
        $sql = "UPDATE products 
            SET name_product=?, description_product=?, price_product=?, image=?, id_category=? 
            WHERE id_product=?";
        return $this->db->execute($sql, "ssdsii", [$name, $desc, $price, $image, $category, $id]);
    }
    //Cập nhật sản phẩm không thay đổi hình ảnh
    public function updateProductNoImage($id, $name, $desc, $price, $category)
    {
        $sql = "UPDATE products
            SET name_product=?, description_product=?, price_product=?, id_category=? 
            WHERE id_product=?";
        return $this->db->execute($sql, "ssdii", [$name, $desc, $price, $category, $id]);
    }
    //Lấy sản phẩm theo danh mục
    public function getProductsByCategory($id_category)
    {
        $sql = "SELECT * FROM products WHERE id_category = ?";
        return $this->db->select($sql, "i", [$id_category]);
    }

}