<?php
require_once 'backend/config/config.php';
require_once 'backend/core/Router.php';
require_once 'backend/core/Database.php';
require_once 'backend/core/View.php';

// Initialize Router
$router = new Router();

// Define routes here
$router->addRoute('GET', '/', function() {
    View::render('home/index');
});

$router->addRoute('GET', '/components', function() {
    View::render('components/index');
});

$router->addRoute('GET', '/about', function() {
    View::render('about/index');
});

$router->addRoute('GET', '/authentification', function() {
    View::render('authentification/login');
});

$router->addRoute('GET', '/authentification', function() {
    View::render('authentification/logout');
});

// Handle the request
$router->handleRequest();