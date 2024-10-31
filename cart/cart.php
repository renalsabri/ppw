<?php
session_start();

if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = array();
    header("Location: cart.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP. CO | View Cart</title>
    <link rel="stylesheet" href="style_cart.css?v=1.0">
    <link rel="icon" type="image/x-icon" href="icon.png">
</head>
<body>
    <nav>
        <div class="header-left">
            <a href="../beranda/beranda.php">
                <img src="logo.jpg" alt="SHOP. CO logo">
            </a>
        </div>
        <div class="header-center">
            <h1>Your Cart</h1>
        </div>
        <div class="header-right">
            <a href="../beranda/beranda.php" class="btn-back">Back to Shop</a>
        </div>
    </nav>

    <section class="cart-list">
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <form method="POST" action="">
                <button type="submit" name="clear_cart" class="btn-clear-cart">Clear Cart</button>
            </form>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2024 E-Commerce. All rights reserved.</p>
    </footer>
</body>
</html>