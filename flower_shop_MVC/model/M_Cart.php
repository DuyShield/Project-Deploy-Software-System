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
                FROM cart c 
                JOIN products p ON c.id_product = p.id_product 
                WHERE c.id_account = ?";
        return $this->db->select($sql, "i", [$id_account]);
    }

    //Thêm hoặc cập nhật số lượng
    public function addToCartDB($id_account, $id_product, $quantity)
    {
        //Kiểm tra cart database
        $sql = "SELECT id_cart, quantity FROM cart WHERE id_account = ? AND id_product = ?";
        $result = $this->db->select($sql, "ii", [$id_account, $id_product]);

        $exists = !empty($result) ? $result[0] : null;

        if ($exists) {
            //Cập nhật quantity
            $new_qty = $exists['quantity'] + $quantity;
            $sql = "UPDATE cart SET quantity = ? WHERE id_cart = ?";
            return $this->db->execute($sql, "ii", [$new_qty, $exists['id_cart']]);
        } else {
            //Thêm mới sản phẩm trong giỏ hàng
            $sql = "INSERT INTO cart (id_account, id_product, quantity) VALUES (?, ?, ?)";
            return $this->db->execute($sql, "iii", [$id_account, $id_product, $quantity]);
        }
    }
}