<?php
require_once "config/database.php";

class M_Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories";
        return $this->db->select($sql);
    }
}
?>