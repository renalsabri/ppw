<?php
session_start();

// Hapus item dari cart
if (isset($_POST['remove_item'])) {
    $product_name = $_POST['product_name'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] === $product_name) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            break;
        }
    }
    header("Location: cart.php");
    exit();
}

// Update kuantitas produk
if (isset($_POST['update_quantity'])) {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $product_name) {
            $item['quantity'] = max(1, intval($quantity)); // Minimal kuantitas 1
            break;
        }
    }
    header("Location: cart.php");
    exit();
}

// Clear semua item di cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
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
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $item):
                        $item_total = $item['price'] * $item['quantity'];
                        $total_price += $item_total;
                    ?>
                        <tr>
                            <td style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" width="50">
                                <?php echo htmlspecialchars($item['name']); ?> <!-- Nama produk termasuk ukuran -->
                            </td>
                            <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                            <td>
                                <form method="POST" action="" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_quantity">Update</button>
                                </form>
                            </td>
                            <td>Rp <?php echo number_format($item_total, 0, ',', '.'); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                    <button type="submit" name="remove_item">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: Rp <?php echo number_format($total_price, 0, ',', '.'); ?></h3>

            <form method="POST" action="">
                <button type="submit" name="clear_cart" style="margin-bottom: 20px;">Clear Cart</button>
            </form>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2024 E-Commerce. All rights reserved.</p>
    </footer>
</body>
</html>