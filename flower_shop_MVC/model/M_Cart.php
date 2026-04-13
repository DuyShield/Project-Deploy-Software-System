<?php
require_once "config/database.php";
class M_Cart
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    //Lấy giỏ hàng
    public function getCartByAccount($id_account)
    {
        $sql = "SELECT c.*, p.name_product, p.price_product, p.image 
                FROM carts c 
                JOIN products p ON c.id_product = p.id_product 
                WHERE c.id_account = ?";
        return $this->db->select($sql, "i", [$id_account]);
    }

    //Thêm hoặc cập nhật số lượng
    public function addToCartDB($id_account, $id_product, $quantity)
    {
        //Kiểm tra cart database
        $sql = "SELECT id_cart, quantity FROM carts WHERE id_account = ? AND id_product = ?";
        $result = $this->db->select($sql, "ii", [$id_account, $id_product]);

        $exists = !empty($result) ? $result[0] : null;

        if ($exists) {
            //Cập nhật quantity
            $new_qty = $exists['quantity'] + $quantity;
            $sql = "UPDATE carts SET quantity = ? WHERE id_cart = ?";
            return $this->db->execute($sql, "ii", [$new_qty, $exists['id_cart']]);
        } else {
            //Thêm mới sản phẩm trong giỏ hàng
            $sql = "INSERT INTO carts (id_account, id_product, quantity) VALUES (?, ?, ?)";
            return $this->db->execute($sql, "iii", [$id_account, $id_product, $quantity]);
        }
    }
    //Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCartDB($id_account, $id_product)
    {
        $sql = "DELETE FROM carts WHERE id_account = ? AND id_product = ?";
        return $this->db->execute($sql, "ii", [$id_account, $id_product]);
    }

    // Tăng hoặc giảm số lượng trong DB
    public function updateQuantityDB($id_account, $id_product, $op)
    {
        // Lấy số lượng hiện tại
        $sql = "SELECT id_cart, quantity FROM carts WHERE id_account = ? AND id_product = ?";
        $result = $this->db->select($sql, "ii", [$id_account, $id_product]);

        if (!empty($result)) {
            $current_qty = $result[0]['quantity'];
            $id_cart = $result[0]['id_cart'];

            if ($op === 'inc') {
                $new_qty = $current_qty + 1;
            } else {
                $new_qty = $current_qty - 1;
            }

            if ($new_qty > 0) {
                $sql = "UPDATE carts SET quantity = ? WHERE id_cart = ?";
                return $this->db->execute($sql, "ii", [$new_qty, $id_cart]);
            } else {
                // Nếu giảm xuống 0 thì xóa luôn
                $sql = "DELETE FROM carts WHERE id_cart = ?";
                return $this->db->execute($sql, "i", [$id_cart]);
            }
        }
    }
    //Xóa toàn bộ giỏ hàng của người dùng
    public function createOrder($id_account, $name, $phone, $email, $address, $note, $total_money, $payment_method)
    {
        $sql = "INSERT INTO orders (id_account, fullname, phone, email, address, note, total_price, payment, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $this->db->execute($sql, "isssssss", [$id_account, $name, $phone, $email, $address, $note, $total_money, $payment_method]);
        $sql_get_id = "SELECT LAST_INSERT_ID() as last_id";
        $res = $this->db->select($sql_get_id);
        return $res[0]['last_id'];
    }
    //Tạo chi tiết đơn hàng
    public function createOrderDetail($id_order, $id_product, $quantity, $price)
    {
        $sql = "INSERT INTO order_details (id_order, id_product, quantity, price) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, "iiii", [$id_order, $id_product, $quantity, $price]);
    }
    //Xóa toàn bộ giỏ hàng của người dùng
    public function clearCart($id_account)
    {
        $sql = "DELETE FROM carts WHERE id_account = ?";
        return $this->db->execute($sql, "i", [$id_account]);
    }
    //Lấy tất cả đơn hàng
    public function getAllOrders()
    {
        $sql = "SELECT * FROM orders ORDER BY created_at";
        return $this->db->select($sql);
    }
    //Lấy chi tiết đơn hàng
    public function getOrderDetail($id_order)
    {
        $sql = "SELECT od.*, p.name_product, p.image 
                FROM order_details od 
                JOIN products p ON od.id_product = p.id_product 
                WHERE od.id_order = ?";
        return $this->db->select($sql, "i", [$id_order]);
    }
    //Cập nhật trạng thái đơn hàng
    public function updateStatus($id_order, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id_order = ?";
        return $this->db->execute($sql, "ii", [$status, $id_order]);
    }
    //Xóa đơn hàng
    public function deleteOrder($id_order)
    {
        // Xóa chi tiết đơn hàng trước
        $sql = "DELETE FROM order_details WHERE id_order = ?";
        $this->db->execute($sql, "i", [$id_order]);
        
        // Xóa đơn hàng
        $sql = "DELETE FROM orders WHERE id_order = ?";
        return $this->db->execute($sql, "i", [$id_order]);
    }
    //Lấy đơn hàng của người dùng
    public function getOrdersByUser($id_user)
    {
        $sql = "SELECT * FROM orders WHERE id_account = ? ORDER BY id_order";
        return $this->db->select($sql, "i", [$id_user]);
    }
    //Lấy thông tin đơn hàng theo ID
    public function getOrderById($id_order)
    {
        $sql = "SELECT * FROM orders WHERE id_order = ?";
        return $this->db->select($sql, "i", [$id_order])[0] ?? null;
    }
    //Lấy chi tiết đơn hàng theo ID đơn hàng
    public function getOrderItems($id_order)
    {
        $sql = "SELECT oi.*, p.name_product, p.image 
            FROM order_details oi 
            JOIN products p ON oi.id_product = p.id_product 
            WHERE oi.id_order = ?";
        return $this->db->select($sql, "i", [$id_order]);
    }
}