<?php
require_once "model/M_Product.php";

class C_Product {

    public function home(){

        $model = new M_Product();
        $products = $model->getAllProducts();

        include "views/home.php";
    }

    public function detail(){

        $id = $_GET['id'];

        $model = new M_Product();
        $product = $model->getProductById($id);

        include "views/product_detail.php";
    }

}