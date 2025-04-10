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

$message = '';
if (isset($_POST['order']) && isset($_POST['quantity'])) {
    $quantity = (int)$_POST['quantity'];
    if ($product->orderProduct($id, $quantity)) {
        $message = 'Order placed successfully!';
        // Refresh product data after order
        $productData = $product->getProductById($id);
    } else {
        $message = 'Failed to place order. Please try again.';
    }
}
?>

<div class="container">
    <?php if ($message): ?>
        <div class="alert <?= strpos($message, 'Failed') === false ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
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
                    <form action="/products/order/<?= $id ?>" method="POST" class="order-form">
                        <div class="quantity-input">
                            <label for="quantity">Quantity:</label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   min="1" 
                                   max="<?= $productData['quantity'] ?>" 
                                   value="1" 
                                   required>
                        </div>
                        <button type="submit" name="order" class="btn btn-primary">
                            Add to Cart
                        </button>
                    </form>
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
.order-form {
    margin-top: 1rem;
}
.quantity-input {
    margin-bottom: 1rem;
}
.quantity-input label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
}
.quantity-input input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>