<?php
require_once 'backend/models/User.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 1) {
    header('Location: /');
    exit();
}

$user = new User();
$users = $user->getAllUsers();
?>

<div class="container mt-4">
    <h2>User Management</h2>
    
    <!-- Add User Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Add New User</h3>
        </div>
        <div class="card-body">
            <form action="/admin/users/add" method="POST">
                <div class="form-group mb-3">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="0">User</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>
    
    <!-- User List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Current Users</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['role'] === 1 ? 'Administrator' : 'User'; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-danger delete-user"
                                            data-id="<?php echo $user['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($user['name']); ?>">
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

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="icon-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h4 class="modal-title mt-3">Are you sure?</h4>
                <p>Do you really want to delete the user <strong id="delete-user-name"></strong>? This action cannot be undone.</p>
                <form action="/admin/users/delete" method="POST" id="deleteUserForm">
                    <input type="hidden" name="user_id" id="delete-user-id">
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
        const deleteButtons = document.querySelectorAll('.delete-user');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        
        // Add click event to each delete button
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                // Set values in the modal
                document.getElementById('delete-user-id').value = id;
                document.getElementById('delete-user-name').textContent = name;
                
                // Show the modal
                deleteModal.show();
            });
        });
        
        // Handle confirm delete button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteUserForm').submit();
        });
    });
</script>