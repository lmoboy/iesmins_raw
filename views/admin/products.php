<pre>

<?php
require_once "products.php"; 
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
    header('Location: /');
    exit();
}

$product = new Product();
$categories = $product->getCategories();
// var_dump($categories);
?>
</pre>
<div class="container mt-4">
    <h2>Product Management</h2>
    
    <!-- Add Product Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Add New Product</h3>
        </div>
        <div class="card-body">
            <form action="/admin/products/add" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="image">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
    
    <!-- Product List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Current Products</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($categories[$product['category_id']-1]['name'] ?? $product['category_id']); ?></td>
                            <td><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td>
                                <form action="/admin/products/delete" method="POST" class="inline-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>