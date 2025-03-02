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

// Example route demonstrating core backend usage
$router->addRoute('GET', '/examples/products', function() {
    require_once 'examples/route_example.php';
    handleUserProducts();
});

// Frontend
$router->addRoute('GET', '/', function() {
    View::render('home/index');
});

$router->addRoute('GET', '/components', function() {
    View::render('components/index');
});

$router->addRoute('GET', '/about', function() {
    View::render('about/index');
});

$router->addRoute('GET', '/authentification/login', function() {
    View::render('authentification/login');
});

$router->addRoute('GET', '/authentification/logout', function() {
    View::render('authentification/logout');
});

// Handle the request
$router->handleRequest();