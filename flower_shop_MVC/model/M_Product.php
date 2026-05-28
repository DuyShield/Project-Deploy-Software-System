<?php
require_once "config/database.php";

class M_Product
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    //Lấy tất cả sản phẩm hoặc lấy theo phân trang
    public function getAllProducts($limit = null, $offset = null)
    {
        $sql = "SELECT p.*, c.name_category FROM products p LEFT JOIN categories c 
        ON p.id_category = c.id_category";
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->select($sql, "ii", [$limit, $offset]);
        }
        return $this->db->select($sql);
    }
    // Lấy tổng số sản phẩm
    public function getProductCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM products";
        $result = $this->db->select($sql);
        return !empty($result) ? (int)$result[0]['count'] : 0;
    }
    //Lấy sản phẩm theo ID
    public function getProductById($id)
    {

        $sql = "SELECT * FROM products WHERE id_product = ?";
        $result = $this->db->select($sql, "i", [$id]);
        return $result[0] ?? null;
    }

    public function getHomeSettings()
    {
        $path = __DIR__ . '/../config/home_settings.json';
        $defaults = [
            'banners' => ['main-banner.jpg', 'main-banner1.jpg', 'main-banner2.jpg'],
            'side_banners' => ['banner1.jpg', 'banner2.jpg'],
            'sale_product_id' => null
        ];

        if (!file_exists($path)) {
            return $defaults;
        }

        $content = file_get_contents($path);
        $data = json_decode($content, true);
        if (!is_array($data)) {
            return $defaults;
        }

        return array_merge($defaults, $data);
    }
    //Lưu cài đặt home banner và sản phẩm sale
    public function saveHomeSettings($settings)
    {
        $path = __DIR__ . '/../config/home_settings.json';
        $json = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($path, $json) !== false;
    }
    //Tìm kiếm sản phẩm theo bộ lọc quản lý
    public function searchProducts($keyword = null, $category = null, $price_min = null, $price_max = null, $stock_status = null)
    {
        $sql = "SELECT p.*, c.name_category 
            FROM products p 
            LEFT JOIN categories c ON p.id_category = c.id_category 
            WHERE 1=1";
        $types = "";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND p.name_product LIKE ?";
            $types .= "s";
            $params[] = "%$keyword%";
        }

        if (!empty($category) && is_numeric($category)) {
            $sql .= " AND p.id_category = ?";
            $types .= "i";
            $params[] = (int)$category;
        }

        if ($price_min !== null && $price_min !== "") {
            $sql .= " AND p.price_product >= ?";
            $types .= "d";
            $params[] = (float)$price_min;
        }

        if ($price_max !== null && $price_max !== "") {
            $sql .= " AND p.price_product <= ?";
            $types .= "d";
            $params[] = (float)$price_max;
        }

        if ($stock_status === 'in_stock') {
            $sql .= " AND p.stock > 0";
        } elseif ($stock_status === 'out_of_stock') {
            $sql .= " AND p.stock <= 0";
        }

        if ($types !== "") {
            return $this->db->select($sql, $types, $params);
        }

        return $this->db->select($sql);
    }
    //Thêm sản phẩm mới
    public function addProduct($name, $description, $price, $image, $id_category, $stock)
    {
        $sql = "INSERT INTO products (name_product, description_product, price_product, image, stock, id_category)
                VALUES (?,?,?,?,?,?)";
        return $this->db->execute($sql, "ssisii", [$name, $description, $price, $image, $stock, $id_category]);
    }
    //Xóa sản phẩm
    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE id_product = ?";
        return $this->db->execute($sql, "i", [$id]);
    }
    //Cập nhật sản phẩm
    public function updateProduct($id, $name, $desc, $price, $image, $category, $stock)
    {
        $sql = "UPDATE products 
            SET name_product=?, description_product=?, price_product=?, image=?, stock=?, id_category=? 
            WHERE id_product=?";
        return $this->db->execute($sql, "ssisdii", [$name, $desc, $price, $image, $stock, $category, $id]);
    }
    //Cập nhật sản phẩm không thay đổi hình ảnh
    public function updateProductNoImage($id, $name, $desc, $price, $category, $stock)
    {
        $sql = "UPDATE products
            SET name_product=?, description_product=?, price_product=?, stock=?, id_category=? 
            WHERE id_product=?";
        return $this->db->execute($sql, "ssidii", [$name, $desc, $price, $stock, $category, $id]);
    }
    //Lấy sản phẩm theo danh mục
    public function getProductsByCategory($id_category)
    {
        $sql = "SELECT * FROM products WHERE id_category = ?";
        return $this->db->select($sql, "i", [$id_category]);
    }
    //Lấy bình luận của sản phẩm
    public function checkUserPurchased($userId, $productId)
    {
        $sql = "SELECT COUNT(*) FROM orders 
            JOIN order_details ON orders.id_order = order_details.id_order 
            WHERE orders.id_account = ? 
            AND order_details.id_product = ? 
            AND orders.status = '2'";
        $stmt = $this->db->select($sql, "ii", [$userId, $productId]);
        return !empty($stmt) && $stmt[0]['COUNT(*)'] > 0;
    }
    // Lấy danh sách bình luận để hiển thị ở trang Detail sản phẩm
    public function getCommentsByProduct($id_product) {
        $sql = "SELECT comments.*, accounts.username 
                FROM comments 
                JOIN accounts ON comments.id_account = accounts.id 
                WHERE id_product = ? AND status = 1 
                ORDER BY created_at DESC";
        return $this->db->select($sql, "i", [$id_product]);
    }
    // Lấy tính điểm trung bình của sản phẩm
    public function getAverageRating($id_product)
    {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM comments 
                WHERE id_product = ? AND status = 1";
        $result = $this->db->select($sql, "i", [$id_product]);
        if (!empty($result)) {
            return [
                'avg_rating' => round($result[0]['avg_rating'] ?? 0, 1),
                'total_reviews' => $result[0]['total_reviews'] ?? 0
            ];
        }
        return ['avg_rating' => 0, 'total_reviews' => 0];
    }
    // Lấy thống kê sao
    public function getStarStats($id_product)
    {
        $stats = [];
        for ($i = 1; $i <= 5; $i++) {
            $sql = "SELECT COUNT(*) as count FROM comments WHERE id_product = ? AND rating = ? AND status = 1";
            $result = $this->db->select($sql, "ii", [$id_product, $i]);
            $stats[$i] = $result[0]['count'] ?? 0;
        }
        return $stats;
    }
    // Kiểm tra user đã review chưa
    public function checkUserReviewed($id_product, $id_user)
    {
        $sql = "SELECT id_product FROM comments WHERE id_product = ? AND id_account = ?";
        $result = $this->db->select($sql, "ii", [$id_product, $id_user]);
        return !empty($result);
    }
    // Lấy comment của user cho sản phẩm
    public function getUserComment($id_product, $id_user)
    {
        $sql = "SELECT * FROM comments WHERE id_product = ? AND id_account = ? ORDER BY created_at DESC LIMIT 1";
        $result = $this->db->select($sql, "ii", [$id_product, $id_user]);
        return !empty($result) ? $result[0] : null;
    }

}