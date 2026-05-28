<?php
require_once "config/database.php";
require_once "controllers/C_Product.php";
require_once "controllers/C_User.php";
require_once "controllers/C_Cart.php";
require_once "controllers/C_Admin.php";

$product = new C_Product();
$user = new C_User();
$cart = new C_Cart();

$action = isset($_GET['action']) ? $_GET['action'] : "home";

switch ($action) {
    case "home":
        $product->home();
        break;
    case 'product':
        $product->home_product();
        break;
    case 'filter_by_category':
        $product->filter_by_category();
        break;
    case "filter_products":
        $product->filter_products();
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
    case "contact_submit":
        $user->contact_submit();
        break;
    case "contact_detail":
        $user_id = $_SESSION['user']['id'] ?? null;
        $user->contact_detail($user_id);
        break;
    case "notifications":
        $user->notifications();
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
    case 'clear_cart':
        $cart->clear_cart();
        break;
    case "checkout":
        $cart->checkout();
        break;
    case "process_checkout":
        $cart->process_checkout();
        break;
    case "order_success":
        $cart->order_success();
        break;
    case "my_orders":
        $user->my_orders();
        break;
    case "add_wishlist":
        $user->add_wishlist();
        break;
    case "my_wishlist":
        $user->my_wishlist();
        break;
    case "order_detail":
        $user->order_detail();
        break;
    case "save_review":
        $user->submit_review();
        break;
    case "write_review":
        $product->review_page();
        break;
    case "profile":
        $user->profile();
        break;
    case "update_profile_info":
        $user->update_profile_info();
        break;
    case "update_avatar":
        $user->update_avatar();
        break;
    case "change_password":
        $user->change_password();
        break;
    case "dashboard":
    case "product_management":
    case "search_product_management":
    case "save_product":
    case "del_product":
    case "up_product":
    case "save_home_settings":
    case "contact_list":
    case "order_lists":
    case "update_status":
    case "delete_order":
    case "send_reply":
    case "delete_contact":
    case "account_management":
    case "change_user_role":
    case "change_user_password":
    case "view_login_history":
    case "banner_sale":
    case "admin_notification_form":
    case "send_notification":
        $admin = new C_Admin();
        // Xử lý các action khác
        if ($action == "dashboard")
            $admin->dashboard();
        if ($action == "product_management")
            $admin->product_management();
        if ($action == "search_product_management")
            $admin->search_product_management();
        if ($action == "save_product")
            $admin->save_product();
        if ($action == "del_product")
            $admin->del_product();
        if ($action == "up_product")
            $admin->update_product();
        if ($action == "save_home_settings")
            $admin->save_home_settings();
        if ($action == "contact_list")
            $admin->contact_list();
        if ($action == "order_lists")
            $admin->order_lists();
        if ($action == "update_status")
            $admin->update_status();
        if ($action == "delete_order")
            $admin->delete_order();
        if ($action == "send_reply")
            $admin->send_reply();
        if ($action == "delete_contact")
            $admin->delete_contact();
        if ($action == "account_management")
            $admin->account_management();
        if ($action == "change_user_role")
            $admin->change_user_role();
        if ($action == "change_user_password")
            $admin->change_user_password();
        if ($action == "view_login_history")
            $admin->view_login_history();
        if ($action == "banner_sale")
            $admin->banner_sale();
        if ($action == "admin_notification_form")
            $admin->notification_form();
        if ($action == "send_notification")
            $admin->send_notification();
        break;
    default:
        echo "404 Not Found";
}