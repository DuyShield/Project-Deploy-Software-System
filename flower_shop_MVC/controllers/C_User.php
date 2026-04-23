<?php
require_once "model/M_User.php";

class C_User
{
    private $model;

    public function __construct()
    {
        $this->model = new M_User();
    }
    //Hiển thị trang đăng nhập
    public function login()
    {
        include "views/login.php";
    }
    //Hiển thị trang đăng ký
    public function register()
    {
        include "views/register.php";
    }
    //Hiển thị trang liên hệ
    public function contact()
    {
        include "views/contact.php";
    }
    //Xử lý đăng ký
    public function register_submit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];
            if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
                echo "Vui lòng nhập đầy đủ thông tin!";
                return;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email không hợp lệ!";
                return;
            }
            if ($password !== $confirm) {
                echo "Mật khẩu không khớp!";
                return;
            }
            if ($this->model->getAccountByName($username)) {
                echo "Tên đăng nhập đã tồn tại!";
                return;
            }
            //Mã hóa password
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->model->insertAccount($username, $email, $hashPassword);
            header("Location: index.php?action=login");
            exit();
        }
    }

    //Xử lý đăng nhập
    public function login_submit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("Location: index.php?action=login"); // Quay lại trang login
                exit();
            }
            $user = $this->model->getAccountByName($username);
            if ($user && password_verify($password, $user['password'])) {
                // Lấy role
                $roleData = $this->model->getRoleByName($username);
                $role = ($roleData) ? trim($roleData['role']) : 'user';
                // Lưu Session chính
                $_SESSION['user'] = $user;
                $_SESSION['role'] = $role;
                // Xử lý Remember Me
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $expire_db = (new DateTime('+7 days'))->format('Y-m-d H:i:s');
                    $this->model->saveRememberToken($user['id'], $token, $expire_db);
                    setcookie("remember_token", $token, [
                        'expires' => time() + (86400 * 7),
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                }
                // Chuyển hướng theo Role
                if ($role === "admin") {
                    header("Location: index.php?action=product_management");
                } else {
                    header("Location: index.php?action=home");
                }
                exit();
            } else {
                $_SESSION['error'] = "Sai tài khoản hoặc mật khẩu!";
                header("Location: index.php?action=login");
                exit();
            }
        }
    }
    // Xử lý đăng xuất
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'] ?? null;
        session_unset();
        session_destroy();
        if (isset($_COOKIE['remember_token'])) {
            setcookie("remember_token", "", time() - 3600, "/");
        }
        if ($user_id) {
            $this->model->clearRememberToken($user_id);
        }
        header("Location: index.php?action=login");
        exit();
    }
    //Xử lý liên hệ
    public function contact_submit()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Lấy User ID từ session
            $user_id = $_SESSION['user']['id'] ?? null;

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            //Kiểm tra trống
            if (empty($name) || empty($email) || empty($message)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("Location: index.php?action=contact");
                exit();
            }
            // Kiểm tra định dạng email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Email không hợp lệ!";
                header("Location: index.php?action=contact");
                exit();
            }
            //Lưu vào db
            $result = $this->model->saveContact($user_id, $name, $email, $message);
            if ($result) {
                $_SESSION['success'] = "Tin nhắn của bạn đã được gửi thành công!";
                header("Location: index.php?action=contact&id=" . ($user_id ?? ''));
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại sau.";
                header("Location: index.php?action=contact");
            }
            exit();
        }
    }
    //Hiển thị chi tiết liên hệ
    public function contact_detail($user_id)
    {
        if (!$user_id) {
            header("Location: index.php?action=login");
            return;
        }
        $contacts = $this->model->getContactsByUserId($user_id);
        include "views/contact_detail.php";
    }
    //Hiển thị lịch sử đơn hàng
    public function my_orders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login&error=denied");
            exit();
        }

        $user_id = $_SESSION['user']['id'];

        require_once "model/M_Cart.php";
        $cartModel = new M_Cart();
        $orders = $cartModel->getOrdersByUser($user_id);

        include "views/my_orders.php";
    }
    //Hiển thị chi tiết đơn hàng
    public function order_detail()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php?action=my_orders");
            exit();
        }

        $id_order = $_GET['id'];
        $cartModel = new M_Cart();

        $order = $cartModel->getOrderById($id_order);

        if (!$order || $order['id_account'] != $_SESSION['user']['id']) {
            die("Bạn không có quyền xem đơn hàng này!");
        }
        $isDelivered = ($order['status'] == 2);
        $totalColumns = $isDelivered ? 5 : 4;
        $items = $cartModel->getOrderItems($id_order);
        include "views/order_detail.php";
    }
    //Xử lý khi khách bấm nút "Gửi đánh giá"
    public function submit_review()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_product = $_POST['id_product'];
            $id_order = $_POST['id_order'];
            $id_user = $_SESSION['user']['id'];
            $content = $_POST['content'];
            $rating = $_POST['rating'];

            $commentModel = new M_User();
            $result = $commentModel->insertComment($id_product, $id_user, $content, $rating, $id_order);

            if ($result) {
                // Lưu thành công thì quay về trang chi tiết đơn hàng
                header("Location: index.php?action=order_detail&id=" . $id_order);
            } else {
                echo "Có lỗi xảy ra khi gửi bình luận.";
            }
        }
    }
    //Thêm sản phẩm vào yêu thích
    public function add_wishlist()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_product = $_POST['id_product'];
            $id_account = $_SESSION['user']['id'];
            $model = new M_User();

            if ($model->addWishlist($id_account, $id_product)) {
                // Gửi trạng thái thành công
                $_SESSION['success'] = "Đã thêm vào danh sách yêu thích!";
            } else {
                // Gửi trạng thái thất bại
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại.";
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    //Xem sản phẩm yêu thích
    public function view_wishlist()
    {
        $userId = $_SESSION['user']['id'];
        $model = new M_User();
        $items = $model->getWishlistByUser($userId);
        include "views/wishlist.php";
    }
    //Xóa sản phẩm khỏi yêu thích
    public function remove_wishlist()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_product = $_POST['id_product']; // Nhận từ input hidden trong modal
            $id_account = $_SESSION['user']['id'];

            $model = new M_User();

            if ($model->removeWishlist($id_account, $id_product)) {
                $_SESSION['success'] = "Sản phẩm đã được xóa khỏi danh sách yêu thích!";
                header("Location: index.php?action=view_wishlist&status=success");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa sản phẩm khỏi danh sách yêu thích.";
                header("Location: index.php?action=view_wishlist&status=error");
                exit();
            }
        }
    }
}