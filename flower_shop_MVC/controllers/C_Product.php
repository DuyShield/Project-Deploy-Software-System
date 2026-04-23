<?php
require_once "model/M_Product.php";
require_once "model/M_Category.php";
class C_Product
{
    //Trang chủ hiển thị tất cả sản phẩm
    public function home()
    {
        $model = new M_Product();
        $products = $model->getAllProducts();
        include "views/home.php";
    }
    //Trang hiển thị tất cả sản phẩm
    public function home_product()
    {
        $model = new M_Product();
        $products = $model->getAllProducts();
        $hideBanner = true;
        include "views/home.php";
    }
    //Trang chi tiết sản phẩm
    public function detail()
    {
        $id = $_GET['id'];
        $model = new M_Product();
        $product = $model->getProductById($id);
        $comments = $model->getCommentsByProduct($id);
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $hasPurchased = $model->checkUserPurchased($userId, $id);
        }
        include "views/product_detail.php";
    }
    //Trang tìm kiếm sản phẩm
    public function search()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $model = new M_Product();
        $products = $model->searchProducts($keyword);
        $isSearch = true;
        include "views/home.php";
    }
    // 1. Hiển thị trang viết đánh giá
    public function write_review()
    {
        $id_product = $_GET['id_product'];
        $id_order = $_GET['id_order'];

        // Lấy thông tin sản phẩm để hiển thị lên form cho khách xem
        $model = new M_Product();
        $product = $model->getProductById($id_product);

        include "views/write_review.php";
    }

    //Trang viết đánh giá
    public function review_page()
    {
        $id_product = $_GET['id_product'];
        $id_order = $_GET['id_order'];
        $model = new M_Product();
        $product = $model->getProductById($id_product);
        include "views/comment.php";
    }

}