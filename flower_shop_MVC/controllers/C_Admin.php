<?php
require_once "model/M_Cart.php";
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
    //Xử lý xóa liên hệ
    public function delete_contact()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $model = new M_User();
            $result = $model->deleteContact($id);
            if ($result) {
                $_SESSION['success'] = "Tin nhắn đã được xóa thành công!";
                header("Location: index.php?action=contact_list&status=deleted");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa tin nhắn!";
                header("Location: index.php?action=contact_list&status=error");
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
                header("Location: index.php?action=contact_list&status=success");
                exit();
            }else {
                $_SESSION['error'] = "Có lỗi xảy ra khi lưu phản hồi!";
                header("Location: index.php?action=contact_list&status=error");
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
            header("Location: index.php?action=order_lists&msg=Updated");
            exit();
        } else {
            $_SESSION['error'] = "Cập nhật trạng thái đơn hàng không thành công. Vui lòng thử lại.";
            header("Location: index.php?action=order_lists");
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
                header("Location: index.php?action=order_lists&status=deleted");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa đơn hàng!";
                header("Location: index.php?action=order_lists&status=error");
                exit();
            }
        }
    }
    //Danh sách sản phẩm
    public function product_management()
    {
        $model = new M_Product();
        $products = $model->getAllProducts();
        $categoryModel = new M_Category();
        $categories = $categoryModel->getAllCategories();
        include "views/product_management.php";
    }
    //Tìm kiếm sản phẩm trong quản lý
    public function search_product_management()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $model = new M_Product();
        $products = $model->searchProducts($keyword);
        $isSearch = true;
        include "views/product_management.php";
    }
    //Xử lý thêm sản phẩm mới
    public function save_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            // Xử lý Upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_ext, $allowed_ext)) {
                    $new_image_name = uniqid() . "_" . time() . "." . $file_ext;
                    echo "Tên file mới: " . $new_image_name . "<br>"; // Debug
                    $target = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_products/" . $new_image_name;

                    if (move_uploaded_file($tmp_name, $target)) {
                        $model = new M_Product();
                        $model->addProduct($name, $description, $price, $new_image_name, $category);
                        $_SESSION['success'] = "Sản phẩm đã được thêm thành công!";
                        header("Location: index.php?action=product_management");
                        exit();
                    } else {
                        $_SESSION['error'] = "Lỗi upload ảnh! Kiểm tra quyền thư mục.";
                        header("Location: index.php?action=product_management");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Chỉ chấp nhận file JPG, PNG, GIF.";
                    header("Location: index.php?action=product_management");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Vui lòng chọn ảnh.";
                header("Location: index.php?action=product_management");
                exit();
            }
        }
    }
    //Xử lý xóa sản phẩm
    public function del_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $_POST['id_product'];
            $model = new M_Product();
            $product = $model->getProductById($id);
            if ($product) {
                // Xóa file ảnh
                $image_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_products/" . $product['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $model->deleteProduct($id);
            }
            $_SESSION['success'] = "Sản phẩm đã được xóa thành công!";
            header("Location: index.php?action=product_management");
            exit();
        } else {
            $_SESSION['error'] = "Không tìm thấy sản phẩm để xóa.";
            header("Location: index.php?action=product_management");
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
                        $model->updateProduct($id, $name, $description, $price, $new_image_name, $category);
                    } else {
                        $_SESSION['error'] = "Lỗi upload ảnh! Kiểm tra quyền thư mục.";
                        header("Location: index.php?action=product_management");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Chỉ chấp nhận file JPG, PNG, GIF.";
                    header("Location: index.php?action=product_management");
                    exit();
                }
            } else {
                //Update sản phẩm khi không có thêm ảnh mới
                $_SESSION['success'] = "Sản phẩm đã được cập nhật thành công!";
                $model->updateProductNoImage($id, $name, $description, $price, $category);
            }
            header("Location: index.php?action=product_management");
            exit();
        }
    }
    
}
?>