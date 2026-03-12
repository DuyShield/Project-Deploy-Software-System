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
}