<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once __DIR__ . '/../backend/core/Database.php';

class ProductSeeder {
    private $db;
    private $products = [
        [
            'name' => 'Classic White Sneakers',
            'price' => 79.99,
            'quantity' => 25,
            'image' => 'sneakers.jpg',
            'category' => 'Footwear'
        ],
        [
            'name' => 'Leather Messenger Bag',
            'price' => 129.99,
            'quantity' => 15,
            'image' => 'bag.jpg',
            'category' => 'Accessories'
        ],
        [
            'name' => 'Wireless Headphones',
            'price' => 199.99,
            'quantity' => 30,
            'image' => 'headphones.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Smart Watch',
            'price' => 249.99,
            'quantity' => 20,
            'image' => 'watch.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Laptop Backpack',
            'price' => 89.99,
            'quantity' => 40,
            'image' => 'backpack.jpg',
            'category' => 'Accessories'
        ],
        [
            'name' => 'Portable Power Bank',
            'price' => 49.99,
            'quantity' => 50,
            'image' => 'powerbank.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Bluetooth Speaker',
            'price' => 159.99,
            'quantity' => 35,
            'image' => 'speaker.jpg',
            'category' => 'Electronics'
        ],
        [
            'name' => 'Gaming Mouse',
            'price' => 69.99,
            'quantity' => 45,
            'image' => 'mouse.jpg',
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
        $sql = "INSERT INTO products (name, price, quantity, image, category) VALUES (:name, :price, :quantity, :image, :category)";
        
        foreach ($this->products as $product) {
            $this->db->query($sql, [
                'name' => $product['name'],
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