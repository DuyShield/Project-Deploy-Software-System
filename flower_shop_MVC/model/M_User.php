<?php
require_once "config/database.php";

class M_User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    //Đăng ký tài khoản mới
    public function insertAccount($username, $email, $password)
    {
        $sql = "INSERT INTO accounts(username, email, password) VALUES (?, ?, ?)";
        return $this->db->execute($sql, "sss", [$username, $email, $password]);
    }
    //Lấy thông tin người dùng theo tên đăng nhập
    public function getAccountByName($username)
    {
        $sql = "SELECT * FROM accounts WHERE username = ?";
        $result = $this->db->select($sql, "s", [$username]);
        return $result[0] ?? null;
    }
    //Lấy vai trò của người dùng theo tên đăng nhập
    public function getRoleByName($username)
    {
        $sql = "SELECT role FROM accounts WHERE username = ?";
        $result = $this->db->select($sql, "s", [$username]);
        return $result[0] ?? null;
    }
    //Lưu token nhớ đăng nhập
    public function saveRememberToken($userId, $token, $expire)
    {
        $sql = "
        UPDATE accounts 
        SET remember_token = ?, token_expire = ? 
        WHERE id = ?";
        return $this->db->execute($sql, "sss", [$token, $expire, $userId]);
    }
    //Lấy người dùng theo token
    public function getUserByToken($token)
    {
        $sql = "
        SELECT * FROM accounts 
        WHERE remember_token = ? 
        AND token_expire > NOW()";
        return $this->db->select($sql, "s", [$token]) ?? null;
    }
    //Xóa token khi người dùng đăng xuất
    public function clearRememberToken($userId)
    {
        $sql = "
        UPDATE accounts 
        SET remember_token = NULL, token_expire = NULL 
        WHERE id = ?";
        return $this->db->execute($sql, "s", [$userId]);
    }
    //Lưu liên hệ từ người dùng
    public function saveContact($user_id, $name, $email, $message)
    {
        $sql = "INSERT INTO contacts (user_id, name, email, message, created_at)
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->execute($sql, "ssss", [$user_id, $name, $email, $message]);
    }
    //Lấy tất cả liên hệ
    public function getAllContacts()
    {
        $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
        return $this->db->select($sql);
    }
    //Lấy liên hệ của người dùng theo ID
    public function getContactsByUserId($user_id)
    {
        $sql = "SELECT * FROM contacts WHERE user_id = ? ORDER BY created_at DESC";
        return $this->db->select($sql, "s", [$user_id]);
    }
    //Xóa liên hệ theo ID
    public function deleteContact($id)
    {
        $sql = "DELETE FROM contacts WHERE id = ?";
        return $this->db->execute($sql, "i", [$id]);
    }
    //Lấy thông tin liên hệ theo ID
    public function getContactById($id)
    {
        $sql = "SELECT * FROM contacts WHERE id = ?";
        return $this->db->select($sql, "i", [$id])[0] ?? null;
    }
    //Cập nhật phản hồi cho liên hệ
    public function updateReply($id, $reply)
    {
        $sql = "UPDATE contacts SET reply = ? WHERE id = ?";
        return $this->db->execute($sql, "si", [$reply, $id]);
    }
    // Lưu bình luận mới
    public function insertComment($id_product, $id_user, $content, $rating, $id_order)
    {
        $sql = "INSERT INTO comments (id_product, id_account, content, rating, id_order, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        return $this->db->execute($sql, "iisii", [$id_product, $id_user, $content, $rating, $id_order]);
    }
    // Thêm sản phẩm vào yêu thích
    public function addWishlist($userId, $productId)
    {
        $sql = "INSERT IGNORE INTO wishlists (id_account, id_product) VALUES (?, ?)";
        return $this->db->execute($sql,"ss", [$userId, $productId]);
    }

    // Xóa khỏi yêu thích
    public function removeWishlist($userId, $productId)
    {
        $sql = "DELETE FROM wishlists WHERE id_account = ? AND id_product = ?";
        return $this->db->execute($sql, "ss", [$userId, $productId]);
    }

    // Lấy danh sách sản phẩm yêu thích của user
    public function getWishlistByUser($userId)
    {
        $sql = "SELECT p.* FROM products p 
                JOIN wishlists w ON p.id_product = w.id_product 
                WHERE w.id_account = ?";
        return $this->db->select($sql, "s", [$userId]);
    }
}