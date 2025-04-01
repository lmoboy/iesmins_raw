
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
                    <textarea class="form-control" id="description" name="description" rows="3" maxlength="255" required></textarea>
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
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-danger delete-product"
                                            data-id="<?php echo $product['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($product['name']); ?>">
                                        Delete
                                        </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="icon-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h4 class="modal-title mt-3">Are you sure?</h4>
                <p>Do you really want to delete the product <strong id="delete-product-name"></strong>? This action cannot be undone.</p>
                <form action="/admin/products/delete" method="POST" id="deleteProductForm">
                    <input type="hidden" name="product_id" id="delete-product-id">
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-product');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
        
        // Add click event to each delete button
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // Set values in the modal
                document.getElementById('delete-product-id').value = id;
                document.getElementById('delete-product-name').textContent = name;
                
                // Show the modal
                deleteModal.show();
            });
        });
        
        // Handle confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteProductForm').submit();
        });
    });
</script>