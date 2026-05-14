<?php
require_once "model/M_Cart.php";
require_once "model/M_Product.php";
require_once "model/M_Category.php";
require_once "model/M_User.php";
class C_Admin
{
    public function __construct()
    {
        //Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=login&error=denied");
            exit();
        }
    }
    //Hiển thị danh sách đơn hàng
    public function order_lists()
    {
        $orderModel = new M_Cart();
        $orders = $orderModel->getAllOrders();
        require "views/order_lists.php";
    }
    //Hiển thị danh sách liên hệ
    public function contact_list()
    {
        $model = new M_User();
        $contacts = $model->getAllContacts();
        if ($contacts === null) {
            $contacts = [];
        }
        include "views/contact_list.php";
    }


    // Phương thức cũ - giữ lại cho compatibility
    public function dashboard()
    {
        $productModel = new M_Product();
        $orderModel = new M_Cart();
        $categoryModel = new M_Category();

        $products = $productModel->getAllProducts();
        $orders = $orderModel->getAllOrders();
        $categories = $categoryModel->getAllCategories();

        $totalProducts = count($products);
        $totalOrders = count($orders);
        $totalStock = 0;

        $statusMap = [
            0 => 'Chờ xử lý',
            1 => 'Đang giao',
            2 => 'Hoàn thành'
        ];

        $statusCounts = [
            'Chờ xử lý' => 0,
            'Đang giao' => 0,
            'Hoàn thành' => 0,
            'Khác' => 0
        ];
        foreach ($orders as $order) {
            $key = $statusMap[$order['status']] ?? 'Khác';
            $statusCounts[$key] = ($statusCounts[$key] ?? 0) + 1;
        }

        $categoryProductCounts = [];
        $categoryStockCounts = [];
        foreach ($categories as $category) {
            $categoryProductCounts[$category['name_category']] = 0;
            $categoryStockCounts[$category['name_category']] = 0;
        }
        foreach ($products as $product) {
            $key = $product['name_category'] ?? 'Không phân loại';
            if (!isset($categoryProductCounts[$key])) {
                $categoryProductCounts[$key] = 0;
            }
            if (!isset($categoryStockCounts[$key])) {
                $categoryStockCounts[$key] = 0;
            }
            $categoryProductCounts[$key]++;
            $productStock = isset($product['stock']) ? (int)$product['stock'] : 0;
            $categoryStockCounts[$key] += $productStock;
            $totalStock += $productStock;
        }

        include "views/dashbroad.php";
    }
    //Xử lý xóa liên hệ
    public function delete_contact()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $model = new M_User();
            $result = $model->deleteContact($id);
            if ($result) {
                $_SESSION['success'] = "Tin nhắn đã được xóa thành công!";
                header("Location: index.php?action=dashboard&tab=contacts");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa tin nhắn!";
                header("Location: index.php?action=dashboard&tab=contacts");
                exit();
            }
        }
    }
    //Xử lý lưu dữ liệu trả lời
    public function send_reply()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $reply = $_POST['reply'];
            $model = new M_User();
            if ($model->updateReply($id, $reply)) {
                $_SESSION['success'] = "Phản hồi đã được lưu thành công!";
                header("Location: index.php?action=dashboard&tab=contacts");
                exit();
            }else {
                $_SESSION['error'] = "Có lỗi xảy ra khi lưu phản hồi!";
                header("Location: index.php?action=dashboard&tab=contacts");
                exit();
            }
        }
    }
    //Cập nhật trạng thái đơn hàng
    public function update_status()
    {
        if (isset($_POST['id_order']) && isset($_POST['status'])) {
            $id_order = $_POST['id_order'];
            $status = $_POST['status'];

            $orderModel = new M_Cart();
            $orderModel->updateStatus($id_order, $status);
            $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công!";
            header("Location: index.php?action=dashboard&tab=orders");
            exit();
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái đơn hàng không thành công. Vui lòng thử lại.";
            header("Location: index.php?action=dashboard&tab=orders");
            exit();
        }
    }
    //Xóa đơn hàng
    public function delete_order()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $orderModel = new M_Cart();
            $result = $orderModel->deleteOrder($id);
            if ($result) {
                $_SESSION['success'] = "Đơn hàng đã được xóa thành công!";
                header("Location: index.php?action=dashboard&tab=orders");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa đơn hàng!";
                header("Location: index.php?action=dashboard&tab=orders");
                exit();
            }
        }
    }
    //Danh sách sản phẩm
    public function product_management()
    {
        $model = new M_Product();
        $products = $model->getAllProducts();
        $allProducts = $model->getAllProducts();
        $categoryModel = new M_Category();
        $categories = $categoryModel->getAllCategories();
        $homeSettings = $model->getHomeSettings();
        include "views/product_management.php";
    }
    //Tìm kiếm sản phẩm trong quản lý
    public function search_product_management()
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
        $category = isset($_GET['category']) ? trim($_GET['category']) : "";
        $price_min = isset($_GET['price_min']) ? trim($_GET['price_min']) : "";
        $price_max = isset($_GET['price_max']) ? trim($_GET['price_max']) : "";
        $stock_status = isset($_GET['stock_status']) ? trim($_GET['stock_status']) : "";

        $model = new M_Product();
        $products = $model->searchProducts($keyword, $category, $price_min, $price_max, $stock_status);
        $allProducts = $model->getAllProducts();
        $categoryModel = new M_Category();
        $categories = $categoryModel->getAllCategories();
        $homeSettings = $model->getHomeSettings();
        $isSearch = true;
        include "views/product_management.php";
    }
    //Xử lý lưu cài đặt home banner và sản phẩm sale
    public function save_home_settings()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=product_management");
            exit();
        }

        $model = new M_Product();
        $homeSettings = $model->getHomeSettings();
        $bannerDir = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_banners/";

        // Xử lý banner chính
        for ($i = 1; $i <= 3; $i++) {
            $field = "main_banner_$i";
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
                $file = $_FILES[$field];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('banner_main_') . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $bannerDir . $newName)) {
                        $homeSettings['banners'][$i - 1] = $newName;
                    }
                }
            }
        }

        // Xử lý banner nhỏ
        for ($i = 1; $i <= 2; $i++) {
            $field = "side_banner_$i";
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
                $file = $_FILES[$field];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('banner_side_') . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $bannerDir . $newName)) {
                        $homeSettings['side_banners'][$i - 1] = $newName;
                    }
                }
            }
        }

        $saleProductId = isset($_POST['sale_product_id']) && $_POST['sale_product_id'] !== '' ? (int)$_POST['sale_product_id'] : null;
        $homeSettings['sale_product_id'] = $saleProductId;

        if ($model->saveHomeSettings($homeSettings)) {
            $_SESSION['success'] = 'Cập nhật banner và sản phẩm sale thành công!';
        } else {
            $_SESSION['error'] = 'Không thể lưu cài đặt trang chủ. Vui lòng thử lại.';
        }

        header("Location: index.php?action=product_management");
        exit();
    }
    //Xử lý thêm sản phẩm mới
    public function save_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $stock = isset($_POST['stock']) ? max(0, (int)$_POST['stock']) : 0;

            // Xử lý Upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_ext, $allowed_ext)) {
                    $new_image_name = uniqid() . "_" . time() . "." . $file_ext;
                    $target = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_products/" . $new_image_name;

                    if (move_uploaded_file($tmp_name, $target)) {
                        $model = new M_Product();
                        $model->addProduct($name, $description, $price, $new_image_name, $category, $stock);
                        $_SESSION['success'] = "Sản phẩm đã được thêm thành công!";
                        header("Location: index.php?action=dashboard&tab=products");
                        exit();
                    } else {
                        $_SESSION['error'] = "Lỗi upload ảnh! Kiểm tra quyền thư mục.";
                        header("Location: index.php?action=dashboard&tab=products");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Chỉ chấp nhận file JPG, PNG, GIF.";
                    header("Location: index.php?action=dashboard&tab=products");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Vui lòng chọn ảnh.";
                header("Location: index.php?action=dashboard&tab=products");
                exit();
            }
        }
    }
    //Xử lý xóa sản phẩm
    public function del_product()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $model = new M_Product();
            $product = $model->getProductById($id);
            if ($product) {
                // Xóa file ảnh
                $image_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_products/" . $product['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $model->deleteProduct($id);
                $_SESSION['success'] = "Sản phẩm đã được xóa thành công!";
            } else {
                $_SESSION['error'] = "Không tìm thấy sản phẩm để xóa.";
            }
            header("Location: index.php?action=dashboard&tab=products");
            exit();
        }
    }
    //Xử lý cập nhật sản phẩm
    public function update_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_product'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $stock = isset($_POST['stock']) ? max(0, (int)$_POST['stock']) : 0;

            $model = new M_Product();
            $oldProduct = $model->getProductById($id);
            $oldImage = $oldProduct['image'];
            //Kiểm tra có chỉnh sửa ảnh mới không
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                //Kiểm tra định dạng ảnh
                if (in_array($file_ext, $allowed_ext)) {
                    $new_image_name = uniqid() . "_" . time() . "." . $file_ext;
                    $target = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_products/" . $new_image_name;
                    if (move_uploaded_file($tmp_name, $target)) {
                        $oldPath = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_products/" . $oldImage;
                        if (!empty($oldImage) && file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                        //Update sản phẩm với ảnh mới
                        $model->updateProduct($id, $name, $description, $price, $new_image_name, $category, $stock);
                    } else {
                        $_SESSION['error'] = "Lỗi upload ảnh! Kiểm tra quyền thư mục.";
                        header("Location: index.php?action=dashboard&tab=products");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Chỉ chấp nhận file JPG, PNG, GIF.";
                    header("Location: index.php?action=dashboard&tab=products");
                    exit();
                }
            } else {
                //Update sản phẩm khi không có thêm ảnh mới
                $model->updateProductNoImage($id, $name, $description, $price, $category, $stock);
            }
            $_SESSION['success'] = "Sản phẩm đã được cập nhật thành công!";
            header("Location: index.php?action=dashboard&tab=products");
            exit();
        }
    }

    // Quản lý tài khoản
    public function account_management()
    {
        $userModel = new M_User();
        $users = $userModel->getAllUsers();
        include "views/account_management.php";
    }

    // Thay đổi role của user
    public function change_user_role()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_POST['user_id'];
            $role = $_POST['role'];

            if (empty($user_id) || empty($role)) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ!";
                header("Location: index.php?action=account_management");
                exit();
            }

            $userModel = new M_User();
            $result = $userModel->updateUserRole($user_id, $role);

            if ($result) {
                $_SESSION['success'] = "Role đã được cập nhật!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật role!";
            }

            header("Location: index.php?action=account_management");
            exit();
        }
    }

    // Đổi mật khẩu cho user (admin)
    public function change_user_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_POST['user_id'];
            $new_password = $_POST['new_password'];

            if (empty($user_id) || empty($new_password)) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ!";
                header("Location: index.php?action=account_management");
                exit();
            }

            // Validate mật khẩu
            if (strlen($new_password) < 8 || !preg_match('/^(?=.*[a-zA-Z])(?=.*\d)/', $new_password)) {
                $_SESSION['error'] = "Mật khẩu phải có ít nhất 8 ký tự và bao gồm chữ lẫn số!";
                header("Location: index.php?action=account_management");
                exit();
            }

            $userModel = new M_User();
            $result = $userModel->adminUpdatePassword($user_id, $new_password);

            if ($result) {
                $_SESSION['success'] = "Mật khẩu đã được cập nhật!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật mật khẩu!";
            }

            header("Location: index.php?action=account_management");
            exit();
        }
    }

    // Xem lịch sử đăng nhập
    public function view_login_history()
    {
        $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

        if ($user_id <= 0) {
            $_SESSION['error'] = "User ID không hợp lệ!";
            header("Location: index.php?action=account_management");
            exit();
        }

        $userModel = new M_User();
        $user = $userModel->getAccountById($user_id);
        $history = $userModel->getLoginHistory($user_id, 20); // Lấy 20 bản ghi gần nhất

        include "views/login_history.php";
    }

    // Quản lý banner và sản phẩm sale
    public function banner_sale()
    {
        $productModel = new M_Product();
        $products = $productModel->getAllProducts();

        include "views/banner_sale.php";
    }
}
?>