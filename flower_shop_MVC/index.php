<?php
require_once "config/database.php";
require_once "controllers/C_Product.php";

$controller = new C_Product();

$action = isset($_GET['action']) ? $_GET['action'] : "home";

switch($action){

    case "home":
        $controller->home();
        break;

    case "detail":
        $controller->detail();
        break;

    default:
        echo "404 Not Found";
}