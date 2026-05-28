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
        $defaultAvatar = "default.jpg"; // Avatar mặc định
        $sql = "INSERT INTO accounts(username, email, password, avatar) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, "ssss", [$username, $email, $password, $defaultAvatar]);
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

    // Thêm thông báo mới
    public function addNotification($title, $content, $notificationType = 'global', $targetUserId = null, $severity = 'info')
    {
        $notificationType = in_array($notificationType, ['global', 'personal']) ? $notificationType : 'global';
        $severity = in_array($severity, ['info', 'warning', 'danger', 'success']) ? $severity : 'info';
        $hasSeverityColumn = !empty($this->db->select("SHOW COLUMNS FROM notifications LIKE 'severity'"));

        // Thông báo global cho toàn bộ hệ thống
        if ($notificationType === 'global' || $targetUserId === null) {
            // Xóa các thông báo global cũ trước khi thêm
            $this->db->execute("DELETE FROM notifications WHERE type = 'global'");

            // Thêm thông báo global mới
            if ($hasSeverityColumn) {
                $insertSql = "INSERT INTO notifications (user_id, title, content, type, severity, is_read, created_at) VALUES (NULL, ?, ?, 'global', ?, 0, NOW())";
                return $this->db->execute($insertSql, "sss", [$title, $content, $severity]);
            } else {
                $insertSql = "INSERT INTO notifications (user_id, title, content, type, is_read, created_at) VALUES (NULL, ?, ?, 'global', 0, NOW())";
                $contentWithSeverity = "[severity:$severity]" . $content;
                return $this->db->execute($insertSql, "ss", [$title, $contentWithSeverity]);
            }
        }

        // Thông báo cá nhân cho user cụ thể
        if ($hasSeverityColumn) {
            $sql = "INSERT INTO notifications (user_id, title, content, type, severity, is_read, created_at) VALUES (?, ?, ?, 'personal', ?, 0, NOW())";
            return $this->db->execute($sql, "issss", [$targetUserId, $title, $content, $severity]);
        } else {
            $sql = "INSERT INTO notifications (user_id, title, content, type, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())";

            // NẾU BẢNG KHÔNG CÓ CỘT SEVERITY: Gộp cả severity vào content như cũ
            $contentToSave = "[severity:$severity]" . $content;
            return $this->db->execute($sql, "isss", [$targetUserId, $title, $contentToSave, 'personal']);
        }
    }
    // Lấy thông báo của user
    public function getNotificationsForUser($userId, $limit = 10)
    {
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        return $this->db->select($sql, "ii", [$userId, $limit]);
    }

    // Lấy thông báo global cho user (dùng khi hiển thị ở trang home)
    public function getGlobalNotificationsForUser($userId = null, $limit = 5)
    {
        if ($userId !== null) {
            // Nếu ĐÃ đăng nhập: Lấy thông báo global chung (user_id IS NULL)
            $sql = "SELECT * FROM notifications 
                WHERE (user_id = ? OR user_id IS NULL) AND type = 'global' 
                ORDER BY created_at DESC LIMIT ?";
            return $this->db->select($sql, "ii", [$userId, $limit]);
        } else {
            // Nếu CHƯA đăng nhập: Chỉ lấy những thông báo global công khai (user_id là NULL)
            $sql = "SELECT * FROM notifications 
                WHERE user_id IS NULL AND type = 'global' 
                ORDER BY created_at DESC LIMIT ?";
            return $this->db->select($sql, "i", [$limit]);
        }
    }
    // Lấy tổng số thông báo của user
    public function getNotificationCount($userId)
    {
        $sql = "SELECT COUNT(*) AS count FROM notifications WHERE user_id = ?";
        $result = $this->db->select($sql, "i", [$userId]);
        return !empty($result) ? (int)$result[0]['count'] : 0;
    }
    // Lấy số lượng thông báo chưa đọc của user
    public function getUnreadNotificationCount($userId)
    {
        $sql = "SELECT COUNT(*) AS count FROM notifications WHERE user_id = ? AND is_read = 0";
        $result = $this->db->select($sql, "i", [$userId]);
        return !empty($result) ? (int)$result[0]['count'] : 0;
    }
    // Đánh dấu tất cả thông báo của user là đã đọc
    public function markNotificationsRead($userId)
    {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        return $this->db->execute($sql, "i", [$userId]);
    }

    public function getWishlistUsersByProduct($productId)
    {
        $sql = "SELECT DISTINCT a.id FROM accounts a 
                JOIN wishlists w ON a.id = w.id_account 
                WHERE w.id_product = ?";
        $result = $this->db->select($sql, "i", [$productId]);
        return array_column($result, 'id');
    }

    // Lưu bình luận mới
    public function insertComment($id_product, $id_user, $content, $rating, $id_order)
    {
        // Kiểm tra xem user đã comment sản phẩm này chưa
        $checkSql = "SELECT id_product FROM comments WHERE id_product = ? AND id_account = ?";
        $checkResult = $this->db->select($checkSql, "ii", [$id_product, $id_user]);

        if (!empty($checkResult)) {
            // Nếu đã comment rồi thì cập nhật
            return $this->updateComment($id_product, $id_user, $content, $rating);
        } else {
            // Nếu chưa comment thì insert
            $sql = "INSERT INTO comments (id_product, id_account, content, rating, id_order, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            return $this->db->execute($sql, "iisii", [$id_product, $id_user, $content, $rating, $id_order]);
        }
    }
    // Cập nhật comment
    public function updateComment($id_product, $id_user, $content, $rating)
    {
        $sql = "UPDATE comments SET content = ?, rating = ?, created_at = NOW() 
                WHERE id_product = ? AND id_account = ?";
        return $this->db->execute($sql, "ssii", [$content, $rating, $id_product, $id_user]);
    }
    // Lấy comment của user cho sản phẩm
    public function getUserComment($id_product, $id_user)
    {
        $sql = "SELECT * FROM comments WHERE id_product = ? AND id_account = ?";
        $result = $this->db->select($sql, "ii", [$id_product, $id_user]);
        return !empty($result) ? $result[0] : null;
    }
    // Thêm sản phẩm vào yêu thích (kiểm tra duplicate trước khi thêm)
    public function addWishlist($userId, $productId)
    {
        // Kiểm tra xem sản phẩm này đã có trong wishlist chưa
        $checkSql = "SELECT id_wishlist FROM wishlists WHERE id_account = ? AND id_product = ?";
        $result = $this->db->select($checkSql, "ii", [$userId, $productId]);

        // Nếu đã tồn tại, không thêm
        if (!empty($result)) {
            return false; // Trả về false vì đã tồn tại
        }

        // Nếu chưa tồn tại, thêm mới
        $sql = "INSERT INTO wishlists (id_account, id_product) VALUES (?, ?)";
        return $this->db->execute($sql, "ii", [$userId, $productId]);
    }

    // Xóa khỏi yêu thích
    public function removeWishlist($userId, $productId)
    {
        $sql = "DELETE FROM wishlists WHERE id_account = ? AND id_product = ?";
        return $this->db->execute($sql, "ii", [$userId, $productId]);
    }

    // Lấy danh sách sản phẩm yêu thích của user
    public function getWishlistByUser($userId)
    {
        $sql = "SELECT p.*, c.name_category FROM products p 
                JOIN wishlists w ON p.id_product = w.id_product 
                JOIN categories c ON p.id_category = c.id_category
                WHERE w.id_account = ?";
        return $this->db->select($sql, "i", [$userId]);
    }

    // Cập nhật thông tin profile
    public function updateProfile($userId, $username = null, $email = null, $avatar = null, $phone = null, $date_of_birth = null)
    {
        // UPDATE động dựa trên những trường có giá trị mới
        $setParts = [];
        $types = "";
        $params = [];
        // Chỉ thêm vào câu lệnh UPDATE những trường có giá trị mới (không null)
        if ($username !== null) {
            $setParts[] = "username = ?";
            $types .= "s";
            $params[] = $username;
        }

        if ($email !== null) {
            $setParts[] = "email = ?";
            $types .= "s";
            $params[] = $email;
        }

        if ($phone !== null) {
            $setParts[] = "phone = ?";
            $types .= "s";
            $params[] = $phone;
        }

        if ($date_of_birth !== null) {
            $setParts[] = "date_of_birth = ?";
            $types .= "s";
            $params[] = $date_of_birth;
        }

        if ($avatar !== null) {
            $setParts[] = "avatar = ?";
            $types .= "s";
            $params[] = $avatar;
        }

        if (empty($setParts)) {
            return false; // Không có gì để cập nhật
        }

        $sql = "UPDATE accounts SET " . implode(", ", $setParts) . " WHERE id = ?";
        $types .= "i";
        $params[] = $userId;

        return $this->db->execute($sql, $types, $params);
    }

    // Cập nhật mật khẩu
    public function updatePassword($userId, $newPassword)
    {
        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE accounts SET password = ? WHERE id = ?";
        return $this->db->execute($sql, "si", [$hashPassword, $userId]);
    }

    // Lấy thông tin user theo ID
    public function getAccountById($userId)
    {
        $sql = "SELECT * FROM accounts WHERE id = ?";
        $result = $this->db->select($sql, "i", [$userId]);
        return $result[0] ?? null;
    }

    // Lưu lịch sử đăng nhập
    public function insertLoginHistory($userId, $ip, $userAgent)
    {
        $sql = "INSERT INTO login_history (id_account, login_at, ip_address, user_agent) VALUES (?, NOW(), ?, ?)";
        return $this->db->execute($sql, "iss", [$userId, $ip, $userAgent]);
    }

    // Lấy lịch sử đăng nhập của user
    public function getLoginHistory($userId, $limit = 10)
    {
        $sql = "SELECT * FROM login_history WHERE id_account = ? ORDER BY login_at DESC LIMIT ?";
        return $this->db->select($sql, "ii", [$userId, $limit]);
    }

    // Lấy tất cả users
    public function getAllUsers()
    {
        $sql = "SELECT id, username, email, role, last_login_at FROM accounts ORDER BY id";
        return $this->db->select($sql);
    }

    // Cập nhật role của user
    public function updateUserRole($userId, $role)
    {
        $sql = "UPDATE accounts SET role = ? WHERE id = ?";
        return $this->db->execute($sql, "si", [$role, $userId]);
    }
    // Cập nhật lịch sử đăng nhập
    public function updateLastLogin($userId)
    {
        $sql = "UPDATE accounts SET last_login_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, "i", [$userId]);
    }
    // Cập nhật mật khẩu cho user (admin)
    public function adminUpdatePassword($userId, $newPassword)
    {
        $hashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE accounts SET password = ? WHERE id = ?";
        return $this->db->execute($sql, "si", [$hashPassword, $userId]);
    }
}
