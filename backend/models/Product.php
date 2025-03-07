<?php
class Product {
    private $db;
    private const LOW_STOCK_THRESHOLD = 20;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }

    public function getAllProducts($page = 1, $limit = 12, $search = '') {
        $offset = (int)(($page - 1) * $limit);
        $limit = (int)$limit;
        $params = ['limit' => $limit, 'offset' => $offset];
        
        if (!empty($search)) {
            $sql = "SELECT * FROM products WHERE name LIKE :search LIMIT :limit OFFSET :offset";
            $params['search'] = "%{$search}%";
        } else {
            $sql = "SELECT * FROM products LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }

    public function getTotalProducts() {
        $sql = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getLowStockProducts() {
        $sql = "SELECT * FROM products WHERE quantity <= :threshold ORDER BY quantity ASC";
        $stmt = $this->db->query($sql, ['threshold' => self::LOW_STOCK_THRESHOLD]);
        return $stmt->fetchAll();
    }

    public function getRecentOrders($limit = 5) {
        $sql = "SELECT o.*, p.name as product_name, p.image as product_image 
               FROM orders o 
               JOIN products p ON o.product_id = p.id 
               ORDER BY o.created_at DESC 
               LIMIT :limit";
        $stmt = $this->db->query($sql, ['limit' => $limit]);
        return $stmt->fetchAll();
    }

    public function getProductsByCategory($category) {
        $sql = "SELECT * FROM products WHERE category = :category";
        $stmt = $this->db->query($sql, ['category' => $category]);
        return $stmt->fetchAll();
    }

    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM products ORDER BY category";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}