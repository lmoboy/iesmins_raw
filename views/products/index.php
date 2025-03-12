<?php
require_once 'backend/models/Product.php';

$product = new Product();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$products = $product->getAllProducts($page, $limit, $search);
$totalProducts = $product->getTotalProducts();
$totalPages = ceil($totalProducts / $limit);
$lowStockProducts = $product->getLowStockProducts();
$recentOrders = $product->getRecentOrders();
$categories = $product->getCategories();
?>

<div class="container">
    <div class="stats-grid">
        <div class="stats-card">
            <h2>Store Statistics</h2>
            <p>Total Products: <span class="highlight"><?= $totalProducts ?></span></p>
        </div>
        
        <div class="stats-card">
            <h2>Low Stock Alert</h2>
            <ul class="alert-list">
                <?php foreach ($lowStockProducts as $product): ?>
                    <li>
                        <span class="product-name"><?= htmlspecialchars($product['name']) ?></span>
                        <span class="stock-warning">(<?= $product['quantity'] ?> left)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="stats-card">
            <h2>Recent Orders</h2>
            <ul class="alert-list">
                <?php foreach ($recentOrders as $order): ?>
                    <li>
                        <span class="product-name"><?= htmlspecialchars($order['product_name']) ?></span>
                        <span class="order-quantity">(<?= $order['quantity'] ?> units)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="categories-section">
        <h2>Categories</h2>
        <div class="category-tags">
            <?php foreach ($categories as $category): ?>
                <a href="/products?category=<?= urlencode($category['id']) ?>" class="category-tag">
                    <?= htmlspecialchars($category['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <h1>Our Products</h1>
    
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="product-image">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-category"><?= htmlspecialchars($product['category_name']) ?></p>
                    <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                    <p class="stock-info"><?= $product['quantity'] ?> in stock</p>
                    <a href="/products/<?= $product['id'] ?>" class="btn">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>