<?php
require_once 'backend/config/config.php';
require_once 'backend/core/Router.php';
require_once 'backend/core/View.php';
require_once 'backend/core/Database.php';
require_once 'backend/models/Product.php';
// Initialize Router
$router = new Router();

// Define routes here
// Backend

$router->addRoute('POST', '/backend/authenticate', function() {
    require_once 'backend/core/authenticate.php';    
});

// Database migrations route
$router->addRoute('GET', '/backend/migrate', function() {
    require_once 'examples/database_examples.php';
    header('Location: /');
    exit();
});


// Admin routes
$router->addRoute('GET', '/admin/products', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    $database = new Database();
    $db = $database->connect();
    $stmt = $db->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll();
    View::render('admin/products', ['products' => $products]);
});

$router->addRoute('POST', '/admin/products/add', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    
    // Create product instance
    $product = new Product();
    
    // Prepare data from form
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'quantity' => $_POST['quantity'],
        'category_id' => $_POST['category_id']
    ];
    
    // Handle image upload
    $imageFile = isset($_FILES['image']) ? $_FILES['image'] : null;
    
    // Add product with image
    $result = $product->addProduct($data, $imageFile);
    
    if (!$result) {
        // Handle error (could add flash message here)
        $_SESSION['error'] = 'Failed to add product. Please try again.';
    }
    
    header('Location: /admin/products');
    exit();
});

$router->addRoute('POST', '/admin/products/delete', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    $database = new Database();
    $db = $database->connect();
    
    $product_id = $_POST['product_id'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    header('Location: /admin/products');
    exit();
});

$router->addRoute('GET', '/admin/categories', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    View::render('admin/categories');
});

$router->addRoute('POST', '/admin/categories/add', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    require_once 'backend/admin/categories.php';
    $controller = new AdminCategoriesController();
    $controller->add();
});

$router->addRoute('POST', '/admin/categories/update', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    require_once 'backend/admin/categories.php';
    $controller = new AdminCategoriesController();
    $controller->update();
});

$router->addRoute('POST', '/admin/categories/delete', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    require_once 'backend/admin/categories.php';
    $controller = new AdminCategoriesController();
    $controller->delete();
});

$router->addRoute('GET', '/admin/users', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    $database = new Database();
    $db = $database->connect();
    $stmt = $db->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll();
    View::render('admin/users', ['users' => $users]);
});

$router->addRoute('POST', '/admin/users/add', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    $database = new Database();
    $db = $database->connect();
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role]);
    
    header('Location: /admin/users');
    exit();
});

$router->addRoute('POST', '/admin/users/delete', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
        header('Location: /authentification/login');
        exit();
    }
    $database = new Database();
    $db = $database->connect();
    
    $user_id = $_POST['user_id'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    header('Location: /admin/users');
    exit();
});
// Frontend
$router->addRoute('GET', '/', function() {
    View::render('products/index');
});


$router->addRoute("POST", '/products/order/:id', function($params) {
    if (!isset($_SESSION['user'])) {
        header('Location: /authentification/login');
        exit();
    }
    $product_id = $params['id'];
    $quantity = $_POST['quantity'];
    $product = new Product();
    $product->orderProduct($product_id, $quantity);
    header('Location: /products/' . $product_id);
    exit();
});


$router->addRoute('GET', '/products', function() {
    View::render('products/index');
});

$router->addRoute('GET', '/products/:id', function($params) {
    $_GET['id'] = $params['id'];
    View::render('products/detail');
});

$router->addRoute('GET', '/authentification/login', function() {
    View::render('authentification/login');
});

$router->addRoute('GET', '/authentification/logout', function() {
    View::render('authentification/logout');
});

// Handle the request
$router->handleRequest();