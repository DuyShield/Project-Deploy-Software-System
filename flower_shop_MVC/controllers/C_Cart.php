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
    //
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
    //Thêm sản phẩm vào giỏ hàng
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
    //Xóa sản phẩm khỏi giỏ hàng
    public function remove_from_cart()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            if (isset($_SESSION['user'])) {
                $modelCart = new M_Cart();
                $modelCart->removeFromCartDB($_SESSION['user']['id'], $id);
            } else {
                unset($_SESSION['cart'][$id]);
            }
        }
        header("Location: index.php?action=cart");
        exit();
    }
    //Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update_cart()
    {
        $id = $_GET['id'] ?? null;
        $op = $_GET['op'] ?? null;

        if ($id && $op) {
            if (isset($_SESSION['user'])) {
                //Cập nhật DB
                $modelCart = new M_Cart();
                $modelCart->updateQuantityDB($_SESSION['user']['id'], $id, $op);
            } else {
                //Cập nhật Session
                if (isset($_SESSION['cart'][$id])) {
                    if ($op === 'inc') {
                        $_SESSION['cart'][$id]['quantity']++;
                    } elseif ($op === 'dec') {
                        $_SESSION['cart'][$id]['quantity']--;
                        if ($_SESSION['cart'][$id]['quantity'] < 1)
                            unset($_SESSION['cart'][$id]);
                    }
                }
            }
        }
        header("Location: index.php?action=cart");
        exit();
    }
    //Xử lý thanh toán
    function checkout()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $cartItems = [];
        $total = 0;

        $id_account = $_SESSION['user']['id'];
        $modelCart = new M_Cart();
        $cartItems = $modelCart->getCartByAccount($id_account);

        foreach ($cartItems as $item) {
            $total += $item['price_product'] * $item['quantity'];
        }
        include "views/checkout.php";
    }
    //Xử lý lưu đơn hàng
    function process_checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_account = $_SESSION['user']['id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $note = $_POST['note'];
            $payment_method = $_POST['payment'];
            $total_money = $_POST['total_price'];

            $address = $_POST['address'];

            $modelCart = new M_Cart();

            $id_order = $modelCart->createOrder($id_account, $name, $phone, $email, $address, $note, $total_money, $payment_method);

            $cartItems = $modelCart->getCartByAccount($id_account);
            foreach ($cartItems as $item) {
                $modelCart->createOrderDetail($id_order, $item['id_product'], $item['quantity'], $item['price_product']);
            }

            $modelCart->clearCart($id_account);

            header("Location: index.php?action=order_success");
            exit();
        }
    }
    //Hiển thị trang thành công sau khi đặt hàng
    public function order_success()
    {
        include "views/order_success.php";
    }
}
?>