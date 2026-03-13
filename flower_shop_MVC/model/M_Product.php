<?php
require_once "config/database.php";

class M_Product {

    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllProducts(){

        $sql = "SELECT * FROM products";
        return $this->db->select($sql);
    }

    public function getProductById($id){

        $sql = "SELECT * FROM products WHERE id_product = ?";
        $result = $this->db->select($sql,"i",[$id]);
        return $result[0] ?? null;
    }
    public function searchProducts($keyword){
        $sql = "SELECT * FROM products Where name_product LIKE ?";
        $keyword = "%$keyword%";
        $result = $this->db->select($sql, "s", [$keyword]);
        return $result;
    }
}