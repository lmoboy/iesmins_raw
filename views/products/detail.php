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
                <img src="/public/uploads/<?php echo htmlspecialchars($productData['image']); ?>" 
                     alt="/public/uploads/wtf.png" 
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