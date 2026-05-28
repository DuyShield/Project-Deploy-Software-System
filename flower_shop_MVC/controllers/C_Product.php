<?php
require_once "model/M_Product.php";
require_once "model/M_Category.php";
require_once "model/M_User.php";
class C_Product
{
    //Trang chủ hiển thị tất cả sản phẩm
    public function home()
    {
        $model = new M_Product();
        $userModel = new M_User();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 8;
        $offset = ($page - 1) * $limit;
        // Lấy sản phẩm theo phân trang
        $products = $model->getAllProducts($limit, $offset);
        $totalProducts = $model->getProductCount();
        $totalPages = max(1, (int) ceil($totalProducts / $limit));
        $homeSettings = $model->getHomeSettings();
        $saleProduct = null;
        $saleProductRating = ['avg_rating' => 0, 'total_reviews' => 0];
        // Lấy thông báo global để hiển thị ở phần chính của trang chủ
        $homeNotifications = [];

        if (!empty($homeSettings['sale_product_id'])) {
            $saleProduct = $model->getProductById((int)$homeSettings['sale_product_id']);
            if (!empty($saleProduct)) {
                $saleProductRating = $model->getAverageRating((int)$homeSettings['sale_product_id']);
            }
        }

        if (isset($_SESSION['user'])) {
            // Ở trang home chỉ lấy thông báo global để hiển thị ở phần chính
            $homeNotifications = $userModel->getGlobalNotificationsForUser($_SESSION['user']['id'], 5);
        }

        include "views/home.php";
    }
    //Trang hiển thị tất cả sản phẩm lọc theo danh mục hoặc tìm kiếm nâng cao
    public function home_product()
    {
        $model = new M_Product();
        $categoryModel = new M_Category();
        $products = $model->getAllProducts();
        $categories = $categoryModel->getAllCategories();
        $hideBanner = true;
        $showFilters = true;
        include "views/home.php";
    }
    //Trang chi tiết sản phẩm
    public function detail()
    {
        $id = $_GET['id'];
        $model = new M_Product();
        $product = $model->getProductById($id);
        $comments = $model->getCommentsByProduct($id);
        
        // Lấy thông tin đánh giá
        $ratingInfo = $model->getAverageRating($id);
        $starStats = $model->getStarStats($id);
        $totalReviews = $ratingInfo['total_reviews'];
        
        $userComment = null;
        $userReviewed = false;
        
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $hasPurchased = $model->checkUserPurchased($userId, $id);
            $userReviewed = $model->checkUserReviewed($id, $userId);
            $userComment = $model->getUserComment($id, $userId);
        }
        include "views/product_detail.php";
    }
    //Lọc sản phẩm theo danh mục
    public function filter_by_category()
    {
        $model = new M_Product();
        $categoryModel = new M_Category();
        $categories = $categoryModel->getAllCategories();
        
        $id_category = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        
        if ($id_category) {
            $products = $model->getProductsByCategory($id_category);
            $categoryName = array_filter($categories, fn($c) => $c['id_category'] == $id_category);
            $categoryName = !empty($categoryName) ? reset($categoryName)['name_category'] : 'Danh mục';
        } else {
            $products = $model->getAllProducts();
            $categoryName = '';
        }
        
        $hideBanner = true;
        $isFilter = true;
        $showFilters = true;
        include "views/home.php";
    }
    //Lọc sản phẩm nâng cao (danh mục, giá, trạng thái)
    public function filter_products()
    {
        $model = new M_Product();
        $categoryModel = new M_Category();
        $categories = $categoryModel->getAllCategories();
        
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $price_min = isset($_GET['price_min']) ? $_GET['price_min'] : null;
        $price_max = isset($_GET['price_max']) ? $_GET['price_max'] : null;
        $stock_status = isset($_GET['stock_status']) ? $_GET['stock_status'] : null;
        
        $products = $model->searchProducts(null, $category, $price_min, $price_max, $stock_status);
        
        $hideBanner = true;
        $isFilter = true;
        $showFilters = true;
        include "views/home.php";
    }
    //Trang tìm kiếm sản phẩm
    public function search()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $model = new M_Product();
        $categoryModel = new M_Category();
        $products = $model->searchProducts($keyword);
        $categories = $categoryModel->getAllCategories(); // Thêm categories để hiển thị bộ lọc
        $isSearch = true;
        $showFilters = true;
        $showSearchKeyword = true; // Biến để hiển thị từ khóa tìm kiếm trên giao diện
        include "views/home.php";
    }
    // Hiển thị trang viết đánh giá
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