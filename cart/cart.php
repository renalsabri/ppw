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
            <h1>Keranjang</h1>
        </div>
        <div class="header-right">
            <a href="../beranda/beranda.php" class="btn-back">Kembali ke Beranda</a>
        </div>
    </nav>

    <section class="cart-list">
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Keranjangmu Kosong</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Kuantitas</th>
                        <th>Total</th>
                        <th>Hapus produk</th>
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
                                    <button type="submit" name="update_quantity">Perbarui</button>
                                </form>
                            </td>
                            <td>Rp <?php echo number_format($item_total, 0, ',', '.'); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                    <button type="submit" name="remove_item">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: Rp <?php echo number_format($total_price, 0, ',', '.'); ?></h3>

            <form method="POST" action="">
                <button type="submit" name="clear_cart" style="margin-bottom: 20px;">Kosongkan Keranjang</button>
            </form>
            <button type="button" class="btn-checkout">Lanjutkan ke Pembayaran</button>
                <div id="paymentModal" class="modal">
                    <div class="modal-content">
                        <iframe src="../checkout/checkout.php" frameborder="0" id="checkoutFrame"></iframe>
                    </div>
                </div>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2024 E-Commerce. All rights reserved.</p>
    </footer>

    <script>
        document.querySelector('.btn-checkout').addEventListener('click', function () {
            const modal = document.getElementById('paymentModal');
            const totalPrice = <?php echo $total_price; ?>;
            const checkoutUrl = '../checkout/checkout.php?price=' + totalPrice;
            modal.style.display = 'block';
            const checkoutFrame = document.getElementById('checkoutFrame');
            checkoutFrame.src = checkoutUrl;
        });

        // Menutup modal ketika pesan "closeCheckout" diterima
        window.addEventListener('message', function (event) {
            if (event.data === 'closeCheckout') {
                const modal = document.getElementById('paymentModal');
                modal.style.display = 'none';
            }
            if (event.data === 'checkoutComplete') {
                const modal = document.getElementById('paymentModal');
                modal.style.display = 'none';

                window.location.href = 'cart.php';
            }
        });
    </script>
</body>
</html>