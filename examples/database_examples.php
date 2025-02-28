<?php
// Initialize Database connection
$db = new Database();
$db->connect();

// Ensure tables exist
$db->generateTables();

// Example 1: Creating a new user
echo "\n=== Creating a new user ===\n";
$userData = [
    'name' => 'admin@admin.admin',
    'email' => 'admin@admin.admin',
    'password' => password_hash('admin@admin.admin', PASSWORD_DEFAULT),
    'role' => 0
];
$userId = $db->create('users', $userData);
echo "Created user with ID: {$userId}\n";

// Example 2: Creating a product
echo "\n=== Creating a new product ===\n";
$productData = [
    'name' => 'Laptop',
    'price' => 999.99,
    'quantity' => 10,
    'image' => 'laptop.jpg'
];
$productId = $db->create('products', $productData);
echo "Created product with ID: {$productId}\n";

// Example 3: Reading users with conditions
echo "\n=== Reading users ===\n";
$conditions = ['email' => 'john@example.com'];
$users = $db->read('users', $conditions);
echo "Found users:\n";
print_r($users);

// Example 4: Reading all products
echo "\n=== Reading all products ===\n";
$products = $db->read('products');
echo "All products:\n";
print_r($products);

// Example 5: Updating a user
echo "\n=== Updating user ===\n";
$updateData = ['name' => 'John Smith'];
$updateConditions = ['id' => $userId];
$db->update('users', $updateData, $updateConditions);
echo "Updated user {$userId}\n";

// Example 6: Updating product quantity
echo "\n=== Updating product quantity ===\n";
$updateData = ['quantity' => 5];
$updateConditions = ['id' => $productId];
$db->update('products', $updateData, $updateConditions);
echo "Updated product {$productId} quantity\n";

// Example 7: Reading specific fields
echo "\n=== Reading specific fields ===\n";
$users = $db->read('users', [], 'name, email');
echo "Users (name and email only):\n";
print_r($users);
