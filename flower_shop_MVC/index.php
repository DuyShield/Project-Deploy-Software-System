<?php
require_once "config/database.php";
require_once "controllers/C_Product.php";
require_once "controllers/C_User.php";
require_once "controllers/C_Cart.php";

$product = new C_Product();
$user = new C_User();
$cart = new C_Cart();

if (!isset($_SESSION['role']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $userToken = $this->model->getUserByToken($token);

    if ($userToken) {
        $_SESSION['user_id'] = $userToken['id'];
        $_SESSION['role'] = $userToken['role'];
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : "home";

switch ($action) {
    case "home":
        $product->home();
        break;
    case 'product':
        $product->home_product();
        break;
    case 'contact':
        $user->contact();
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
    case "product_management":
        $product->product_management();
        break;
    case "search_product_management":
        $product->search_product_management();
        break;
    case "save_product":
        $product->save_product();
        break;
    case "del_product":
        $product->del_product();
        break;
    case "up_product":
        $product->update_product();
        break;
    case "contact_submit":
        $user->contact_submit();
        break;
    case "contact_list":
        $user->contact_list();
        break;
    case "contact_detail":
        $user_id = $_SESSION['user']['id'] ?? null;
        $user->contact_detail($user_id);
        break;
    case "send_reply":
        $user->send_reply();
        break;
    case 'delete_contact':
        $user->delete();
        break;
    case "cart":
        $cart->cart();
        break;
    case "add_to_cart":
        $cart->add_to_cart();
        break;
    case "update_cart":
        $cart->update_cart();
        break;
    case 'remove_from_cart':
        $cart->remove_from_cart();
        break;
    default:
        echo "404 Not Found";
}