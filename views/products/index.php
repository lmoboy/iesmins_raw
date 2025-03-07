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
                <a href="/products?category=<?= urlencode($category) ?>" class="category-tag">
                    <?= htmlspecialchars($category) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <h1>Our Products</h1>
    
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="/public/images/<?= htmlspecialchars($product['image']) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" 
                     class="product-image">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-category"><?= htmlspecialchars($product['category']) ?></p>
                    <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                    <p class="stock-info"><?= $product['quantity'] ?> in stock</p>
                    <a href="/products/<?= $product['id'] ?>" class="btn">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: var(--spacing-unit);
    margin-bottom: var(--spacing-unit);
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.stats-card {
    background-color: white;
    padding: var(--spacing-unit);
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stats-card h2 {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: var(--spacing-unit);
    color: var(--primary-color);
}

.alert-list {
    list-style: none;
}

.alert-list li {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.product-name {
    font-weight: 500;
}

.stock-warning {
    color: #e74c3c;
    margin-left: 0.5rem;
}

.order-quantity {
    color: var(--text-color);
    opacity: 0.7;
    margin-left: 0.5rem;
}

.categories-section {
    margin-bottom: var(--spacing-unit);
}

.category-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.category-tag {
    padding: 0.5rem 1rem;
    background-color: var(--background-color);
    border-radius: 9999px;
    text-decoration: none;
    color: var(--text-color);
    transition: background-color 0.3s;
}

.category-tag:hover {
    background-color: var(--secondary-color);
    color: white;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: var(--spacing-unit);
}

@media (min-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1280px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.product-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.product-card:hover {
    transform: translateY(-4px);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-info {
    padding: var(--spacing-unit);
}

.product-info h3 {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.product-category {
    color: var(--text-color);
    opacity: 0.7;
    margin-bottom: 0.5rem;
}

.product-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--secondary-color);
}

.stock-info {
    font-size: 0.875rem;
    color: var(--text-color);
    opacity: 0.7;
    margin: 0.5rem 0;
}
</style>