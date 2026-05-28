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

    //Hiển thị trang thông báo
    public function notifications()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $userId = $_SESSION['user']['id'];
        $notifications = $this->model->getNotificationsForUser($userId, 100);
        $this->model->markNotificationsRead($userId);
        include "views/notifications.php";
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
                $_SESSION['user']['role'] = $role;
                $_SESSION['role'] = $role;

                // Lưu lịch sử đăng nhập
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                $this->model->insertLoginHistory($user['id'], $ip, $userAgent);

                // Cập nhật last_login_at
                $this->model->updateLastLogin($user['id']);

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
                    $_SESSION['success'] = "Đăng nhập thành công!";
                    header("Location: index.php?action=product_management");
                } else {
                    $_SESSION['success'] = "Đăng nhập thành công!";
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
    //Hiển thị trang thông tin cá nhân
    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $user_id = $_SESSION['user']['id'];
        $user = $this->model->getAccountById($user_id);
        include "views/profile.php";
    }

    //Cập nhật thông tin cá nhân (tên, email)
    public function update_profile_info()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $date_of_birth = trim($_POST['date_of_birth']);
            
            if (empty($username) || empty($email) || empty($phone) || empty($date_of_birth)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("Location: index.php?action=profile");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Email không hợp lệ!";
                header("Location: index.php?action=profile");
                exit();
            }

            // Kiểm tra username trùng
            $existingUser = $this->model->getAccountByName($username);
            if ($existingUser && $existingUser['id'] != $user_id) {
                $_SESSION['error'] = "Tên đăng nhập đã tồn tại!";
                header("Location: index.php?action=profile");
                exit();
            }

            // Cập nhật profile (không có avatar)
            $result = $this->model->updateProfile($user_id, $username, $email, null, $phone, $date_of_birth);
            if ($result) {
                // Cập nhật session
                $_SESSION['user']['username'] = $username;
                $_SESSION['user']['email'] = $email;
                $_SESSION['success'] = "Cập nhật thông tin thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật!";
            }

            header("Location: index.php?action=profile");
            exit();
        }
    }

    //Cập nhật avatar
    public function update_avatar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];

            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] != 0) {
                $_SESSION['error'] = "Vui lòng chọn file avatar!";
                header("Location: index.php?action=profile");
                exit();
            }

            $file = $_FILES['avatar'];
            $file_size = $file['size'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if ($file_size > 5 * 1024 * 1024) { // 5MB
                $_SESSION['error'] = "Kích thước file không được vượt quá 5MB!";
                header("Location: index.php?action=profile");
                exit();
            }

            if (!in_array($file_ext, $allowed_ext)) {
                $_SESSION['error'] = "Chỉ chấp nhận file JPG, PNG, GIF!";
                header("Location: index.php?action=profile");
                exit();
            }

            // Xóa avatar cũ nếu không phải mặc định
            $currentUser = $this->model->getAccountById($user_id);
            if ($currentUser && $currentUser['avatar'] && $currentUser['avatar'] != 'default.jpg') {
                $oldAvatarPath = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_avatar_users/" . $currentUser['avatar'];
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            // Upload avatar mới
            $new_avatar_name = uniqid() . "_" . time() . "." . $file_ext;
            $target = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/image_avatar_users/" . $new_avatar_name;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Cập nhật chỉ avatar
                $result = $this->model->updateProfile($user_id, null, null, $new_avatar_name);
                if ($result) {
                    // Cập nhật session
                    $_SESSION['user']['avatar'] = $new_avatar_name;
                    $_SESSION['success'] = "Cập nhật avatar thành công!";
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật avatar!";
                }
            } else {
                $_SESSION['error'] = "Lỗi upload avatar!";
            }

            header("Location: index.php?action=profile");
            exit();
        }
    }

    //Đổi mật khẩu
    public function change_password()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("Location: index.php?action=profile");
                exit();
            }

            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu mới không khớp!";
                header("Location: index.php?action=profile");
                exit();
            }

        // Kiểm tra độ dài mật khẩu
        if (strlen($new_password) < 8) {
            $_SESSION['error'] = "Mật khẩu mới phải có ít nhất 8 ký tự!";
            header("Location: index.php?action=profile");
            exit();
        }

        // Kiểm tra mật khẩu có chứa cả chữ và số
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)/', $new_password)) {
            $_SESSION['error'] = "Mật khẩu mới phải bao gồm cả chữ cái và số!";
            header("Location: index.php?action=profile");
            exit();
        }

        // Kiểm tra mật khẩu hiện tại
        $user = $this->model->getAccountById($user_id);
        if (!$user || !password_verify($current_password, $user['password'])) {
            $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
            header("Location: index.php?action=profile");
            exit();
        }

        // Kiểm tra mật khẩu mới không trùng với mật khẩu hiện tại
        if (password_verify($new_password, $user['password'])) {
            $_SESSION['error'] = "Mật khẩu mới không được trùng với mật khẩu hiện tại!";
            header("Location: index.php?action=profile");
            exit();
        }

        // Cập nhật mật khẩu
        $result = $this->model->updatePassword($user_id, $new_password);
        if ($result) {
            $_SESSION['success'] = "Đổi mật khẩu thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi đổi mật khẩu!";
        }

            header("Location: index.php?action=profile");
            exit();
        }
    }
    

    //Hiển thị chi tiết đơn hàng
    public function order_detail()
    {
        // Kiểm tra session và quyền truy cập
        if (!isset($_GET['id'])) {
            header("Location: index.php?action=my_orders");
            exit();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        //
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $id_order = $_GET['id'];
        $cartModel = new M_Cart();

        $order = $cartModel->getOrderById($id_order);

        if (!$order) {
            die("Đơn hàng không tồn tại!");
        }

        $userRole = $_SESSION['user']['role'] ?? $_SESSION['role'] ?? null;
        $isOwner = ($order['id_account'] == $_SESSION['user']['id']);
        $isAdmin = ($userRole === 'admin');

        if (!$isOwner && !$isAdmin) {
            die("Bạn không có quyền xem đơn hàng này!");
        }
        // Kiểm tra trạng thái đơn hàng để hiển thị cột "Đánh giá"
        $isDelivered = ($order['status'] == 2);
        $totalColumns = $isDelivered ? 5 : 4;
        $isMyOrder = $isOwner;
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
                // Kiểm tra xem user đã review trước đó chưa
                if ($commentModel->getUserComment($id_product, $id_user)) {
                    $_SESSION['success'] = "Đánh giá của bạn đã được cập nhật thành công!";
                } else {
                    $_SESSION['success'] = "Cảm ơn bạn đã đánh giá sản phẩm này!";
                }
                // Quay về trang chi tiết sản phẩm
                header("Location: index.php?action=detail&id=" . $id_product);
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi gửi bình luận.";
                header("Location: index.php?action=detail&id=" . $id_product);
            }
            exit();
        }
    }
    //Thêm sản phẩm vào yêu thích
    public function add_wishlist()
    {
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra user đã đăng nhập
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để thêm vào danh sách yêu thích!";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate input
            if (!isset($_POST['id_product']) || empty($_POST['id_product'])) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ. Vui lòng thử lại.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $id_product = (int)$_POST['id_product'];
            $id_account = (int)$_SESSION['user']['id'];
            $model = new M_User();

            // Kiểm tra sản phẩm tồn tại
            if ($id_product <= 0) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ. Vui lòng thử lại.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            }

            $result = $model->addWishlist($id_account, $id_product);
            
            if ($result) {
                $_SESSION['success'] = "Đã thêm vào danh sách yêu thích!";
            } else {
                $_SESSION['error'] = "Sản phẩm này đã có trong danh sách yêu thích hoặc có lỗi xảy ra.";
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
    //Xem sản phẩm yêu thích
    public function my_wishlist()
    {
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra user đã đăng nhập
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để xem danh sách yêu thích!";
            header("Location: index.php?action=login");
            exit();
        }

        $userId = (int)$_SESSION['user']['id'];
        $model = new M_User();
        $items = $model->getWishlistByUser($userId);
        include "views/wishlist.php";
    }
    //Xóa sản phẩm khỏi yêu thích
    public function remove_wishlist()
    {
        // Kiểm tra session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra user đã đăng nhập
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập!";
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate input
            if (!isset($_POST['id_product']) || empty($_POST['id_product'])) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ.";
                header("Location: index.php?action=my_wishlist");
                exit();
            }

            $id_product = (int)$_POST['id_product'];
            $id_account = (int)$_SESSION['user']['id'];

            if ($id_product <= 0 || $id_account <= 0) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ.";
                header("Location: index.php?action=my_wishlist");
                exit();
            }

            $model = new M_User();

            if ($model->removeWishlist($id_account, $id_product)) {
                $_SESSION['success'] = "Sản phẩm đã được xóa khỏi danh sách yêu thích!";
                header("Location: index.php?action=my_wishlist");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa sản phẩm khỏi danh sách yêu thích.";
                header("Location: index.php?action=my_wishlist");
                exit();
            }
        }
    }
}