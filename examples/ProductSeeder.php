<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/core/Database.php';

class ProductSeeder {
    private $db;
    private $products = [
        [
            'name' => 'Classic White Sneakers',
            'description' => 'Comfortable and stylish white sneakers for everyday wear',
            'price' => 79.99,
            'quantity' => 25,
            'image' => 'classical-white-sneakers.jpg',
            'category' => 'Footwear'
        ],
        [
            'name' => 'Leather Messenger Bag',
            'description' => 'Premium leather messenger bag perfect for work or travel',
            'price' => 129.99,
            'quantity' => 15,
            'image' => 'leather-messenger-bag.jpg',
            'category' => 'Accessories'
        ],
        [
            'name' => 'Wireless Headphones',
            'description' => 'High-quality wireless headphones with noise cancellation',
            'price' => 199.99,
            'quantity' => 30,
            'image' => 'wireless-headphones.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Smart Watch',
            'description' => 'Feature-rich smartwatch with fitness tracking capabilities',
            'price' => 249.99,
            'quantity' => 20,
            'image' => 'smart-watch.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Laptop Backpack',
            'description' => 'Durable backpack with padded laptop compartment',
            'price' => 89.99,
            'quantity' => 40,
            'image' => 'laptop-backpack.jpg',
            'category' => 'Accessories'
        ],
        [
            'name' => 'Portable Power Bank',
            'description' => 'High-capacity power bank for charging multiple devices',
            'price' => 49.99,
            'quantity' => 50,
            'image' => 'portable-power-bank.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Bluetooth Speaker',
            'description' => 'Portable Bluetooth speaker with rich sound quality',
            'price' => 159.99,
            'quantity' => 35,
            'image' => 'bluetooth-speaker.jpeg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Gaming Mouse',
            'description' => 'High-precision gaming mouse with customizable buttons',
            'price' => 69.99,
            'quantity' => 45,
            'image' => 'gaming-mouse.jpg',
            'category' => 'Electronics'
        ]
    ];

    public function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }

    public function seed() {
        // First delete records from orders table since it has foreign key constraints
        $this->db->query("DELETE FROM orders");
        
        // Then delete existing products
        $this->db->query("DELETE FROM products");

        // Insert new products
        $sql = "INSERT INTO products (name, description, price, quantity, image, category) VALUES (:name, :description, :price, :quantity, :image, :category)";
        
        foreach ($this->products as $product) {
            $this->db->query($sql, [
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'image' => $product['image'],
                'category' => $product['category']
            ]);
        }

        echo "Products seeded successfully!\n";
    }
}

// Run the seeder
$seeder = new ProductSeeder();
$seeder->seed();