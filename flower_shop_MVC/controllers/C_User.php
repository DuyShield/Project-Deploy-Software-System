<?php
require_once "model/M_User.php";

class C_User
{
    private $model;

    public function __construct()
    {
        $this->model = new M_User();
    }

    public function login()
    {
        include "views/login.php";
    }

    public function register()
    {
        include "views/register.php";
    }
    public function contact()
    {
        include "views/contact.php";
    }
    public function contact_list()
    {
        $contacts = $this->model->getAllContacts();
        if ($contacts === null) {
            $contacts = [];
        }
        include "views/contact_list.php";
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
            //Gọi session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            if (empty($username) || empty($password)) {
                echo "Vui lòng nhập đầy đủ thông tin!";
                return;
            }
            $user = $this->model->getAccountByName($username);
            if ($user && password_verify($password, $user['password'])) {
                //Check role user
                $role = $this->model->getRoleByName($username);
                //lưu session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $role['role'];
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $expire = (new DateTime('+7 days'))->format('Y-m-d H:i:s');
                    //lưu DB
                    $this->model->saveRememberToken($user['id'], $token, $expire);
                    //cookie
                    setcookie("remember_token", $token, [
                        'expires' => time() + (86400 * 7),
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                }
                if (trim($role['role']) === "admin") {
                    //Tạo session
                    $_SESSION['user'] = $user;
                    header("Location: index.php?action=product_management");
                    exit();
                } else {
                    //Tạo session
                    $_SESSION['user'] = $user;
                    header("Location: index.php?action=home");
                    exit();
                }
            } else {
                echo "Sai tài khoản hoặc mật khẩu!";
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
            // Lấy User ID từ session
            $user_id = $_SESSION['user']['id'] ?? null;

            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            // Kiểm tra trống
            if (empty($name) || empty($email) || empty($message)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
                header("Location: index.php?action=contact"); // Trở về trang gửi liên hệ
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
                header("Location: index.php?action=contact_detail&id=" . ($user_id ?? ''));
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại sau.";
                header("Location: index.php?action=contact");
            }
            exit();
        }
    }
    public function contact_detail($user_id)
    {
        if (!$user_id) {
            header("Location: index.php?action=login");
            return;
        }
        $contacts = $this->model->getContactsByUserId($user_id);
        include "views/contact_detail.php";
    }
    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $this->model->deleteContact($id);
            if ($result) {
                header("Location: index.php?action=contact_list&status=deleted");
                exit();
            } else {
                echo "Có lỗi xảy ra khi xóa!";
            }
        }
    }
    //Xử lý lưu dữ liệu trả lời
    public function send_reply() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $reply = $_POST['reply'];
        if ($this->model->updateReply($id, $reply)) {
            header("Location: index.php?action=contact_list&status=success");
            exit();
        }
    }
}
}