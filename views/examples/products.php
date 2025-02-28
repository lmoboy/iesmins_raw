<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo View::escape($title); ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .product-table th, .product-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .product-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .product-table tr:hover {
            background-color: #f9f9f9;
        }
        .sort-link {
            color: #333;
            text-decoration: none;
        }
        .sort-link:hover {
            color: #007bff;
        }
        .sort-indicator {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo View::escape($title); ?></h1>
        
        <?php if (empty($products)): ?>
            <p>No products found.</p>
        <?php else: ?>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>
                            <a href="?sort=name&order=<?php echo $sort_column === 'name' && $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>" class="sort-link">
                                Name
                                <?php if ($sort_column === 'name'): ?>
                                    <span class="sort-indicator"><?php echo $sort_order === 'ASC' ? '↑' : '↓'; ?></span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=price&order=<?php echo $sort_column === 'price' && $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>" class="sort-link">
                                Price
                                <?php if ($sort_column === 'price'): ?>
                                    <span class="sort-indicator"><?php echo $sort_order === 'ASC' ? '↑' : '↓'; ?></span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?sort=quantity&order=<?php echo $sort_column === 'quantity' && $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>" class="sort-link">
                                Quantity
                                <?php if ($sort_column === 'quantity'): ?>
                                    <span class="sort-indicator"><?php echo $sort_order === 'ASC' ? '↑' : '↓'; ?></span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo View::escape($product['name']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="/public/images/<?php echo View::escape($product['image']); ?>" 
                                         alt="<?php echo View::escape($product['name']); ?>" 
                                         style="max-width: 50px; height: auto;">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <script src="/public/js/app.js"></script>
</body>
</html>