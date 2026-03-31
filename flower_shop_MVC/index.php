<?php
require_once "config/database.php";
require_once "controllers/C_Product.php";
require_once "controllers/C_User.php";
require_once "controllers/C_Cart.php";

$product = new C_Product();
$user = new C_User();
$cart = new C_Cart();
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
    case "home_admin":
        $product->home_admin();
        break;
    case "search_admin_home":
        $product->search_admin_home();
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