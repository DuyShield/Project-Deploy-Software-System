<?php
require_once "model/M_Cart.php";
class C_Cart
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function cart()
    {
        $cartItems = [];
        $total = 0;

        if (isset($_SESSION['user'])) {
            //For user has account
            $id_account = $_SESSION['user']['id'];
            $modelCart = new M_Cart();
            $cartItems = $modelCart->getCartByAccount($id_account);
        } else {
            //For user guest
            $cartItems = $_SESSION['cart'] ?? [];
        }
        //Tính tổng tiền
        foreach ($cartItems as $item) {
            $total += $item['price_product'] * $item['quantity'];
        }
        include "views/cart.php";
    }
    public function add_to_cart()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_product'];
            $quantity = $_POST['quantity'];

            if (isset($_SESSION['user'])) {
                //For user use account
                $id_account = $_SESSION['user']['id'];
                $modelCart = new M_Cart();
                $modelCart->addToCartDB($id_account, $id, $quantity);
            } else {
                //For user use guest account
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$id] = [
                        'id_product' => $id,
                        'name_product' => $_POST['name_product'],
                        'price_product' => $_POST['price_product'],
                        'image' => $_POST['image'],
                        'quantity' => $quantity
                    ];
                }
            }
            header("Location: index.php?action=cart");
            exit();

        }
    }
    public function update_cart()
    {
        $id = $_GET['id'] ?? null;
        $op = $_GET['op'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            if ($op === 'inc') {
                $_SESSION['cart'][$id]['quantity'] += 1;
            } elseif ($op === 'dec') {
                $_SESSION['cart'][$id]['quantity'] -= 1;
                // Nếu giảm xuống 0 thì xóa luôn sản phẩm
                if ($_SESSION['cart'][$id]['quantity'] < 1) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }
        header("Location: index.php?action=cart");
        exit();
    }
}
?>