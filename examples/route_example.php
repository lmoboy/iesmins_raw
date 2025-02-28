<?php
// Example route handler function
function handleUserProducts() {
    // Initialize Database
    $database = new Database();
    $db = $database->connect();

    try {
        // Get sorting parameters
        $sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'name';
        $sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        
        // Validate sort column to prevent SQL injection
        $allowed_columns = ['name', 'price', 'quantity'];
        if (!in_array($sort_column, $allowed_columns)) {
            $sort_column = 'name';
        }
        
        // Validate sort order
        $sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';
        
        // Get products with sorting
        $query = "SELECT * FROM products ORDER BY {$sort_column} {$sort_order} LIMIT 10";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Pass data to view
        View::render('examples/products', [
            'products' => $products,
            'title' => 'Product List',
            'sort_column' => $sort_column,
            'sort_order' => $sort_order
        ]);

    } catch (PDOException $e) {

        View::render('error', [
            'message' => 'Database error occurred'
        ]);
    }
}