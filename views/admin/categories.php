<?php
require_once 'backend/models/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
    header('Location: /');
    exit();
}

$category = new Category();
$categories = $category->getAllCategories();
$categoriesWithCount = $category->getProductCountByCategory();
?>

<div class="container mt-4">
    <h2>Category Management</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Add Category Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Add New Category</h3>
        </div>
        <div class="card-body">
            <form action="/admin/categories/add" method="POST">
                <div class="form-group mb-3">
                    <label for="name">Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" maxlength="255"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
    </div>
    
    <!-- Category List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Current Categories</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categoriesWithCount as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['id']); ?></td>
                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                            <td><?php 
                                // Find the description from the categories array
                                $description = '';
                                foreach ($categories as $cat) {
                                    if ($cat['id'] == $category['id']) {
                                        $description = $cat['description'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($description); 
                            ?></td>
                            <td><?php echo htmlspecialchars($category['product_count']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-primary edit-category" 
                                            data-id="<?php echo $category['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($category['name']); ?>"
                                            data-description="<?php 
                                                echo htmlspecialchars($description); 
                                            ?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-danger delete-category"
                                            data-id="<?php echo $category['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($category['name']); ?>">
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

<!-- Edit Category Modal -->
<div class="modal" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/categories/update" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit-name">Category Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-description">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" rows="3" maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="icon-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h4 class="modal-title mt-3">Are you sure?</h4>
                <p>Do you really want to delete the category <strong id="delete-category-name"></strong>? This action cannot be undone and will only work if no products are using this category.</p>
                <form action="/admin/categories/delete" method="POST" id="deleteCategoryForm">
                    <input type="hidden" name="category_id" id="delete-category-id">
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
        // Get all edit buttons
        const editButtons = document.querySelectorAll('.edit-category');
        
        // Add click event to each edit button
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                
                // Set values in the modal form
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-description').value = description;
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
                modal.show();
            });
        });
        
        // Get all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-category');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
        
        // Add click event to each delete button
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // Set values in the modal
                document.getElementById('delete-category-id').value = id;
                document.getElementById('delete-category-name').textContent = name;
                
                // Show the modal
                deleteModal.show();
            });
        });
        
        // Handle confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteCategoryForm').submit();
        });
    });
</script>