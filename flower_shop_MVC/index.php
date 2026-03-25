<?php
require_once "config/database.php";
require_once "controllers/C_Product.php";
require_once "controllers/C_User.php";
$product = new C_Product();
$user = new C_User();
$action = isset($_GET['action']) ? $_GET['action'] : "home";
switch ($action) {
    case "home":
        $product->home();
        break;
    case "detail":
        $product->detail();
        break;
    case "search":
        $product->search();
        break;
    case "login":
        $user->login();
        break;
    case "register":
        $user->register();
        break;
    case "login_submit":
        $user->login_submit();
        break;
    case "register_submit":
        $user->register_submit();
        break;
    case "logout":
        $user->logout();
        break;    
    default:
        echo "404 Not Found";
}