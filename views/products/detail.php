<?php
require_once 'backend/models/Product.php';

$product = new Product();
$uri = $_SERVER['REQUEST_URI'];
$id = 0;

if (preg_match('/\/products\/(\d+)/', $uri, $matches)) {
    $id = (int)$matches[1];
} elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}

if (!$id) {
    error_log("[Product Detail] No product ID provided");
    header('Location: /products');
    exit();
}

$productData = $product->getProductById($id);

if (!$productData) {
    error_log("[Product Detail] Product not found with ID: " . $id);
    header('Location: /products');
    exit();
}
?>

<div class="container">
    <a href="/products" class="back-link">
        <span class="back-arrow">←</span> Back to Products
    </a>

    <div class="product-detail-card">
        <div class="product-detail-grid">
            <div class="product-image-container">
                <img src="/public/images/<?php echo htmlspecialchars($productData['image']); ?>" 
                     alt="<?php echo htmlspecialchars($productData['name']); ?>" 
                     class="product-detail-image">
            </div>
            <div class="product-detail-info">
                <h1><?php echo htmlspecialchars($productData['name']); ?></h1>
                
                <div class="product-meta">
                    <p class="product-price">
                        $<?php echo number_format($productData['price'], 2); ?>
                    </p>
                    <p class="stock-status">
                        Stock: <span class="stock-amount"><?php echo $productData['quantity']; ?> units</span>
                    </p>
                </div>

                <?php if ($productData['quantity'] > 0): ?>
                <button class="btn btn-primary">
                    Add to Cart
                </button>
                <?php else: ?>
                <button class="btn btn-disabled" disabled>
                    Out of Stock
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.back-link {
    display: inline-block;
    margin-bottom: var(--spacing-unit);
    color: var(--secondary-color);
    text-decoration: none;
    transition: color 0.3s;
}

.back-link:hover {
    color: var(--primary-color);
}

.back-arrow {
    display: inline-block;
    margin-right: 0.5rem;
}

.product-detail-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.product-detail-grid {
    display: grid;
    grid-template-columns: 1fr;
}

@media (min-width: 768px) {
    .product-detail-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.product-image-container {
    width: 100%;
}

.product-detail-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.product-detail-info {
    padding: var(--spacing-unit);
}

.product-detail-info h1 {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: var(--spacing-unit);
}

.product-meta {
    margin-bottom: var(--spacing-unit);
}

.product-price {
    font-size: 2rem;
    font-weight: bold;
    color: var(--secondary-color);
    margin-bottom: 0.5rem;
}

.stock-status {
    color: var(--text-color);
    font-size: 0.875rem;
}

.stock-amount {
    font-weight: 500;
}

.btn {
    width: 100%;
    padding: 1rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: var(--secondary-color);
    color: white;
}

.btn-primary:hover {
    background-color: var(--primary-color);
}

.btn-disabled {
    background-color: #ccc;
    color: white;
    cursor: not-allowed;
}
</style>