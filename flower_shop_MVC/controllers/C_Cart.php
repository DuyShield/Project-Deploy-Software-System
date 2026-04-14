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

    //Xóa toàn bộ sản phẩm trong giỏ hàng
    public function clear_cart()
    {
        if (isset($_SESSION['user'])) {
            $modelCart = new M_Cart();
            $modelCart->clearCart($_SESSION['user']['id']);
        } else {
            unset($_SESSION['cart']);
        }
        header("Location: index.php?action=cart");
        exit();
    }
    //Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update_cart()
    {
        $id = $_GET['id'] ?? null;
        $op = $_GET['op'] ?? null;
        $isAjax = isset($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        if ($id && $op) {
            if (isset($_SESSION['user'])) {
                //Cập nhật DB
                $modelCart = new M_Cart();
                $modelCart->updateQuantityDB($_SESSION['user']['id'], $id, $op);
                $cartItems = $modelCart->getCartByAccount($_SESSION['user']['id']);
            } else {
                //Cập nhật Session
                if (isset($_SESSION['cart'][$id])) {
                    if ($op === 'inc') {
                        $_SESSION['cart'][$id]['quantity']++;
                    } elseif ($op === 'dec') {
                        $_SESSION['cart'][$id]['quantity']--;
                        if ($_SESSION['cart'][$id]['quantity'] < 1) {
                            unset($_SESSION['cart'][$id]);
                        }
                    }
                }
                $cartItems = $_SESSION['cart'] ?? [];
            }

            $updatedQuantity = 0;
            $itemPrice = 0;
            $itemExists = false;
            $cartTotal = 0;

            foreach ($cartItems as $itemKey => $item) {
                $productId = $item['id_product'] ?? $itemKey;
                $cartTotal += $item['price_product'] * $item['quantity'];
                if ((string)$productId === (string)$id) {
                    $updatedQuantity = $item['quantity'];
                    $itemPrice = $item['price_product'];
                    $itemExists = true;
                }
            }

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'item_id' => $id,
                    'quantity' => $updatedQuantity,
                    'item_total' => $itemPrice * $updatedQuantity,
                    'cart_total' => $cartTotal,
                    'removed' => !$itemExists,
                ]);
                exit();
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
        $selectedItems = [];

        $id_account = $_SESSION['user']['id'];
        $modelCart = new M_Cart();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_items'])) {
            $selectedItems = array_map('intval', (array)$_POST['selected_items']);
            $cartItems = $modelCart->getCartByAccount($id_account, $selectedItems);
        } else {
            $cartItems = $modelCart->getCartByAccount($id_account);
        }

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
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $note = trim($_POST['note'] ?? '');
            $payment_method = $_POST['payment'] ?? 'cod';
            $total_money = $_POST['total_price'] ?? 0;
            $address = trim($_POST['address'] ?? '');
            $selectedItems = !empty($_POST['selected_items']) ? array_map('intval', (array)$_POST['selected_items']) : [];

            if ($name === '' || $email === '' || $phone === '' || $address === '') {
                $_SESSION['checkout_error'] = 'Vui lòng điền đầy đủ họ tên, email, số điện thoại và địa chỉ.';
                header('Location: index.php?action=checkout');
                exit();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['checkout_error'] = 'Email không hợp lệ.';
                header('Location: index.php?action=checkout');
                exit();
            }
            if (!preg_match('/^[0-9]{10}$/', $phone)) {
                $_SESSION['checkout_error'] = 'Số điện thoại phải gồm đúng 10 chữ số.';
                header('Location: index.php?action=checkout');
                exit();
            }

            $modelCart = new M_Cart();
            $cartItems = !empty($selectedItems)
                ? $modelCart->getCartByAccount($id_account, $selectedItems)
                : $modelCart->getCartByAccount($id_account);

            if (empty($cartItems)) {
                $_SESSION['checkout_error'] = 'Không có sản phẩm để thanh toán.';
                header('Location: index.php?action=cart');
                exit();
            }

            $id_order = $modelCart->createOrder($id_account, $name, $phone, $email, $address, $note, $total_money, $payment_method);

            foreach ($cartItems as $item) {
                $modelCart->createOrderDetail($id_order, $item['id_product'], $item['quantity'], $item['price_product']);
            }

            if (!empty($selectedItems)) {
                $modelCart->removeCartItems($id_account, $selectedItems);
            } else {
                $modelCart->clearCart($id_account);
            }

            header('Location: index.php?action=order_success');
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