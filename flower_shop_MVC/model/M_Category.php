<?php
require_once "config/database.php";

class M_Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    //Lấy tất cả danh mục
    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        return $this->db->select($sql);
    }
    //Lấy tên danh mục theo ID
     public function getCategoryByID($id_category)
    {
        $sql = "SELECT name_category FROM categories WHERE id_category = ?";
        $result = $this->db->select($sql, "i", [$id_category]);
        return $result[0]['name_category'] ?? null;
    }
}
?>