<pre>

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
        
        $sql = "SELECT p.*, c.name as category_name FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id ";
        
        if (!empty($search)) {
            // Check if search is numeric (likely a category ID)
            if (is_numeric($search)) {
                $sql .= "WHERE p.category_id = :category_id ";
                $params['category_id'] = $search;
            } else {
                // Text search for product name
                $sql .= "WHERE p.name LIKE :search ";
                $params['search'] = "%{$search}%";
            }
        }
        
        $sql .= "LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id 
               WHERE p.id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }

    public function getTotalProducts($search = '') {
        $params = [];
        $sql = "SELECT COUNT(*) as total FROM products";
        
        if (!empty($search)) {
            // Check if search is numeric (likely a category ID)
            if (is_numeric($search)) {
                $sql .= " WHERE category_id = :category_id";
                $params['category_id'] = $search;
            } else {
                // Text search for product name
                $sql .= " WHERE name LIKE :search";
                $params['search'] = "%{$search}%";
            }
        }
        
        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getLowStockProducts() {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id 
               WHERE p.quantity <= :threshold ORDER BY p.quantity ASC";
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

    public function getProductsByCategory($category_id) {
        $sql = "SELECT p.*, c.name as category_name FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id 
               WHERE p.category_id = :category_id";
        $stmt = $this->db->query($sql, ['category_id' => $category_id]);
        return $stmt->fetchAll();
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function addProduct($data, $image) {
        try {
            $sql = "INSERT INTO products (name, description, price, quantity, category_id, image) 
                    VALUES (:name, :description, :price, :quantity, :category_id, :image)";
            
            $params = [
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'quantity' => $data['quantity'],
                'category_id' => $data['category_id'],
                'image' => $image
            ];
            
            $stmt = $this->db->query($sql, $params);
            return true;
        } catch (PDOException $e) {
            error_log("Error adding product: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->db->query($sql, ['id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }
}

?>
</pre>
