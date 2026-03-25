<?php
require_once "config/database.php";

class M_User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insertUser($username, $email, $password)
    {
        $sql = "INSERT INTO account(username, email, password) VALUES (?, ?, ?)";
        return $this->db->execute($sql, "sss", [$username, $email, $password]);
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM account WHERE username = ?";
        $result = $this->db->select($sql, "s", [$username]);
        return $result[0] ?? null;
    }
}