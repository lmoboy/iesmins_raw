<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<?php
if (!isset($_SESSION["user"])) {
    require_once 'views/authentification/login.php';
    return;
} else {
?>

    <body>
        <button class="sidebar-toggle" id="sidebar-toggle">☰</button>
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
        <div class="sidebar">
            <div class="sidebar-profile">
                <div class="user-name"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                <div class="user-role"><?= $_SESSION['user']['role'] === 1 ? 'Administrator' : 'User' ?></div>
                <a href="/authentification/logout" class="btn btn-danger">Logout</a>
            </div>
            <ul class="sidebar-nav">
                <li><a href="/products">Products</a></li>
                <?php if ($_SESSION['user']['role'] === 1): ?>
                    <li><a href="/admin/categories">Category Management</a></li>
                    <li><a href="/admin/products">Product Management</a></li>
                    <li><a href="/admin/users">User Management</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <header>
            <nav>
                <h1><?= APP_NAME ?></h1>
                <ul>
                    <li>
                        <form action="/products" method="GET" class="search-form">
                            <input type="search" name="search" placeholder="Search products..."
                                class="search-input">
                            <button type="submit">
                                Search
                            </button>
                        </form>
                    </li>

                    <li>
                        <button id="theme-toggle" class="btn" style="background: var(--primary-color);">
                            Toggle Theme
                        </button>
                    </li>
                </ul>
            </nav>
        </header>

        <main>
            <?= $content ?>
        </main>

        <footer>
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/public/js/helpers.js"></script>
        <script src="/public/js/theme.js"></script>
        <script src="/public/js/app.js"></script>
    </body>
<?php
}
?>

</html>