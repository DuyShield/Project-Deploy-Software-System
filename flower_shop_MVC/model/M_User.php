<?php
require_once "config/database.php";

class M_User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insertAccount($username, $email, $password)
    {
        $sql = "INSERT INTO accounts(username, email, password) VALUES (?, ?, ?)";
        return $this->db->execute($sql, "sss", [$username, $email, $password]);
    }

    public function getAccountByName($username)
    {
        $sql = "SELECT * FROM accounts WHERE username = ?";
        $result = $this->db->select($sql, "s", [$username]);
        return $result[0] ?? null;
    }
    public function getRoleByName($username)
    {
        $sql = "SELECT role FROM accounts WHERE username = ?";
        $result = $this->db->select($sql, "s", [$username]);
        return $result[0] ?? null;
    }
    public function saveRememberToken($userId, $token, $expire)
    {
        $sql = "
        UPDATE accounts 
        SET remember_token = ?, token_expire = ? 
        WHERE id = ?";
        return $this->db->execute($sql, "sss", [$token, $expire, $userId]);
    }
    public function getUserByToken($token)
    {
        $sql = "
        SELECT * FROM accounts 
        WHERE remember_token = ? 
        AND token_expire > NOW()";
        return $this->db->select($sql, "s", [$token]) ?? null;
    }
    public function clearRememberToken($userId)
    {
        $sql = "
        UPDATE accounts 
        SET remember_token = NULL, token_expire = NULL 
        WHERE id = ?";
        return $this->db->execute($sql, "s", [$userId]);
    }
    public function saveContact($user_id, $name, $email, $message)
    {
        $sql = "INSERT INTO contacts (user_id, name, email, message, created_at)
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->execute($sql, "ssss", [$user_id, $name, $email, $message]);
    }
    public function getAllContacts()
    {
        $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
        return $this->db->select($sql);
    }
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
}