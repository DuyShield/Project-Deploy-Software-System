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
            if ($this->model->getUserByUsername($username)) {
                echo "Tên đăng nhập đã tồn tại!";
                return;
            }
            //Mã hóa password
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->model->insertUser($username, $email, $hashPassword);
            header("Location: index.php?action=login");
            exit();
        }
    }

    //Xử lý đăng nhập
    public function login_submit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            if (empty($username) || empty($password)) {
                echo "Vui lòng nhập đầy đủ thông tin!";
                return;
            }
            $user = $this->model->getUserByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                //Check session
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                //Tạo session
                $_SESSION['user'] = $user;
                header("Location: index.php?action=home");
                exit();

            } else {
                echo "Sai tài khoản hoặc mật khẩu!";
            }
        }
    }
    //Xử lý đăng xuất
    public function logout() {
    //Check session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    //Xóa session
    session_unset();
    session_destroy();
    header("Location: index.php?action=login");
    exit();
}
}