<?php
require_once 'backend/config/config.php';
require_once 'backend/core/Router.php';
require_once 'backend/core/View.php';
require_once 'backend/core/Database.php';

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
    $database = new Database();
    $db = $database->connect();
    
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    $stmt = $db->prepare("INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $quantity]);
    
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

// Frontend
$router->addRoute('GET', '/', function() {
    View::render('products/index');
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