<?php
require_once 'backend/models/Category.php';

class AdminCategoriesController {
    private $category;
    
    public function __construct() {
        $this->category = new Category();
    }
    
    public function index() {
        require_once 'views/admin/categories.php';
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            if (empty($_POST['name'])) {
                $_SESSION['error'] = 'Category name is required';
                header('Location: /admin/categories');
                exit();
            }
            
            $data = [
                'name' => trim($_POST['name']),
                'description' => isset($_POST['description']) ? trim($_POST['description']) : ''
            ];
            
            if ($this->category->addCategory($data)) {
                $_SESSION['success'] = 'Category added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add category';
            }
            
            header('Location: /admin/categories');
            exit();
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            if (empty($_POST['id']) || empty($_POST['name'])) {
                $_SESSION['error'] = 'Category ID and name are required';
                header('Location: /admin/categories');
                exit();
            }
            
            $data = [
                'id' => (int)$_POST['id'],
                'name' => trim($_POST['name']),
                'description' => isset($_POST['description']) ? trim($_POST['description']) : ''
            ];
            
            if ($this->category->updateCategory($data)) {
                $_SESSION['success'] = 'Category updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update category';
            }
            
            header('Location: /admin/categories');
            exit();
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
            $category_id = (int)$_POST['category_id'];
            
            if ($this->category->deleteCategory($category_id)) {
                $_SESSION['success'] = 'Category deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete category. Make sure no products are using this category.';
            }
            
            header('Location: /admin/categories');
            exit();
        }
    }
}