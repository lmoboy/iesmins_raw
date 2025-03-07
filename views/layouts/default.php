<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1><?= APP_NAME ?></h1>
            <ul>
                <li>
                    <form action="/products" method="GET" class="search-form" style="display: inline-block;">
                        <input type="search" name="search" placeholder="Search products..." 
                               class="search-input" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                        <button type="submit" style="padding: 5px 10px; border-radius: 4px; background: #4a5568; color: white; border: none;">
                            Search
                        </button>
                    </form>
                </li>
                
                <!-- <?php
                echo '<pre>';
                echo 'Session ID: ' . session_id() . "\n";
                echo 'Session Status: ' . session_status() . "\n";
                echo 'Session Cookie Parameters: ';
                var_dump(session_get_cookie_params());
                echo 'Session Data: ';
                var_dump($_SESSION);
                echo '</pre>';
                ?> -->

                <?php if (isset($_SESSION['user'])): ?>
                    <li style="color: white;">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></li>
                    <li><a href="/authentification/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/authentification/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
    </footer>

    <script src="/public/js/helpers.js"></script>
    <script src="/public/js/app.js"></script>
</body>
</html>