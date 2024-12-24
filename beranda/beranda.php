<?php
session_start();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP. CO | Beranda</title>
    <link rel="stylesheet" href="style_beranda.css?v=1.0">
    <link rel="icon" type="image/x-icon" href="image/icon.png">

</head>
<body>  
    <nav>
        <div class="header-left">
            <a href="">
            <img src="image/logo.jpg" alt="SHOP. CO logo">
            </a>
        </div>
        <div class="header-center">
            <h1>Selamat Datang di SHOP. CO</h1>
            <p>Temukan Produk Terbaik Disini!</p>
        </div>
        <div class="header-right profile-menu">
            <button class="profile-button">Profile</button>
            <div class="profile-dropdown">
            <div class="profile-welcome">
                <center>
                    <p class="name">Hi, 
                    <?php
                    // Cek apakah nama sudah diset dalam sesi
                    if (isset($_SESSION['nama'])) {
                        echo htmlspecialchars($_SESSION['nama']); // Tampilkan nama pengguna
                    } else {
                        echo "Guest"; // Tampilkan pesan default jika nama belum diset
                    }
                    ?>
                    </p>
                </center>
            </div>
                <a href="../user/user.html">Pengguna</a>
                <a href="../cart/cart.php">Keranjang</a>
                <a href="../kelola/barang.php">Kelola Barang</a>
                <a href="../register/register.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-product">
        <div class="product-category">
            <h2>Pakaian</h2>
            <section class="product-list">
                <?php
                $products = [
                    ['name' => 'Baju Jirai Kei', 'price' => 140000, 'image' => '../foto/Baju Jirai Kei-1.jpg', 'link' => '../deskripsi/bajuJiraiKei.php'],
                    ['name' => 'Jersey Bola Real Madrid', 'price' => 120000, 'image' => '../foto/Kaos madrid.jpg', 'link' => '../deskripsi/jerseyBola.php'],
                    ['name' => 'Celana Jeans', 'price' => 500000, 'image' => '../foto/Celana Jeans-1.jpg', 'link' => '../deskripsi/celanaJeans.php'],
                    ['name' => 'Baju Pramuka', 'price' => 85000, 'image' => '../foto/Baju Pramuka-1.jpg', 'link' => '../deskripsi/bajuPramuka.php'],
                    ['name' => 'Rok Jeans', 'price' => 70000, 'image' => '../foto/Rok Jeans-1.jpg', 'link' => '../deskripsi/rokJeans.php']
                ];

                foreach ($products as $product) {
                    echo "<div class='product-item'>";
                    echo "<a href='{$product['link']}'>";
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>";
                    echo "</a>";
                    echo "<h3>{$product['name']}</h3>";
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='product_name' value='{$product['name']}'>";
                    echo "<input type='hidden' name='product_price' value='{$product['price']}'>";
                    echo "<input type='hidden' name='product_image' value='{$product['image']}'>";
                    echo "</form>";
                    echo "</div>";
                }
                ?>
            </section>
        </div>
    </div>

    <div class="container-product">
        <div class="product-category">
            <h2>Kosmetik</h2>
            <section class="product-list">
                <?php
                $products = [
                    ['name' => 'Masker Wajah', 'price' => 23000, 'image' => '../foto/Masker Wajah.jpg', 'link' => '../deskripsi/maskerWajah.php'],
                    ['name' => 'Skincare', 'price' => 350000, 'image' => '../foto/Skincare 1.jpg', 'link' => '../deskripsi/skincare.php'],
                    ['name' => 'Lipstick', 'price' => 100000, 'image' => '../foto/Lipstick 1.jpg', 'link' => '../deskripsi/lipstick.php'],
                    ['name' => 'Cusion', 'price' => 75000, 'image' => '../foto/Cusion 1.jpg', 'link' => '../deskripsi/cusion.php'],
                    ['name' => 'Lip Balm', 'price' => 50000, 'image' => '../foto/Lipbalm 1.jpg', 'link' => '../deskripsi/lipBalm.php']
                ];

                foreach ($products as $product) {
                    echo "<div class='product-item'>";
                    echo "<a href='{$product['link']}'>";
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>";
                    echo "</a>";
                    echo "<h3>{$product['name']}</h3>";
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='product_name' value='{$product['name']}'>";
                    echo "<input type='hidden' name='product_price' value='{$product['price']}'>";
                    echo "<input type='hidden' name='product_image' value='{$product['image']}'>";
                    echo "</form>";
                    echo "</div>";
                }
                ?>
            </section>
        </div>
    </div>

    <div class="container-product">
        <div class="product-category">
            <h2>Anime</h2>
            <section class="product-list">
                <?php
                $products = [
                    ['name' => 'Action Figure Tobi', 'price' => 550000, 'image' => '../foto/Action Figure Tobi.jpg', 'link' => '../deskripsi/actionFigureTobi.php'],
                    ['name' => 'Jaket Kiana', 'price' => 300000, 'image' => '../foto/Jaket Kiana 1.jpg', 'link' => '../deskripsi/jaketKiana.php'],
                    ['name' => 'Replika Pedang Kirito', 'price' => 235000, 'image' => '../foto/Replika Pedang Kirito.jpg', 'link' => '../deskripsi/replikaPedangKirito.php'],
                    ['name' => 'Hoodie One Piece', 'price' => 90000, 'image' => '../foto/Hoodie One Piece.jpg', 'link' => '../deskripsi/hoodieOnePiece.php'],
                    ['name' => 'T-Shirt Gojo Satoru', 'price' => 200000, 'image' => '../foto/Kaos Gojo.jpg', 'link' => '../deskripsi/kaosGojo.php']
                ];

                foreach ($products as $product) {
                    echo "<div class='product-item'>";
                    echo "<a href='{$product['link']}'>";
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>";
                    echo "</a>";
                    echo "<h3>{$product['name']}</h3>";
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='product_name' value='{$product['name']}'>";
                    echo "<input type='hidden' name='product_price' value='{$product['price']}'>";
                    echo "<input type='hidden' name='product_image' value='{$product['image']}'>";
                    echo "</form>";
                    echo "</div>";
                }
                ?>
            </section>
        </div>
    </div>
    
    <footer>
        <p>© 2024 SHOP.CO. All rights reserved.</p>
    </footer>
</body>
</html>