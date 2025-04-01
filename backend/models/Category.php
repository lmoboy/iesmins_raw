<?php
class Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }
    
    /**
     * Error handling helper method
     * @param Exception $e The exception to log
     * @param string $operation The operation being performed
     * @return bool Always returns false to indicate failure
     */
    private function handleError($e, $operation) {
        error_log("Error {$operation} category: " . $e->getMessage());
        return false;
    }
    

    /**
     * Get all categories ordered by name
     * @return array Array of categories
     */
    public function getAllCategories() {
        try {
            $sql = "SELECT * FROM categories ORDER BY name";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $this->handleError($e, 'retrieving');
        }
    }

    /**
     * Get a category by its ID
     * @param int $id Category ID
     * @return array|bool Category data or false on failure
     */
    public function getCategoryById($id) {
        try {
            $sql = "SELECT * FROM categories WHERE id = :id";
            $stmt = $this->db->query($sql, ['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return $this->handleError($e, 'retrieving');
        }
    }

    /**
     * Add a new category
     * @param array $data Category data
     * @return bool Success status
     */
    public function addCategory($data) {
        try {
            // Using the create method from Database class
            $this->db->create('categories', [
                'name' => $data['name'],
                'description' => $data['description']
            ]);
            return true;
        } catch (PDOException $e) {
            return $this->handleError($e, 'adding');
        }
    }

    /**
     * Update an existing category
     * @param array $data Category data including ID
     * @return bool Success status
     */
    public function updateCategory($data) {
        try {
            // Using the update method from Database class
            $this->db->update('categories', 
                [
                    'name' => $data['name'],
                    'description' => $data['description']
                ],
                ['id' => $data['id']]
            );
            return true;
        } catch (PDOException $e) {
            return $this->handleError($e, 'updating');
        }
    }

    /**
     * Delete a category if it's not being used by any products
     * @param int $id Category ID
     * @return bool Success status
     */
    public function deleteCategory($id) {
        try {
            // First check if category is being used by any products
            $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = :id";
            $stmt = $this->db->query($sql, ['id' => $id]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                return false; // Category is in use, cannot delete
            }
            
            // Using the delete method from Database class
            $this->db->delete('categories', ['id' => $id]);
            return true;
        } catch (PDOException $e) {
            return $this->handleError($e, 'deleting');
        }
    }

    /**
     * Get count of products in each category
     * @return array Categories with product counts
     */
    public function getProductCountByCategory() {
        try {
            $sql = "SELECT c.id, c.name, COUNT(p.id) as product_count 
                   FROM categories c 
                   LEFT JOIN products p ON c.id = p.category_id 
                   GROUP BY c.id, c.name 
                   ORDER BY c.name";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return $this->handleError($e, 'retrieving product count');
        }
    }
}