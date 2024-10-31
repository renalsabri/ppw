<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product = [
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'image' => $_POST['product_image']
    ];
    $_SESSION['cart'][] = $product;
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP. CO | Beranda</title>
    <link rel="stylesheet" href="style_beranda.css?v=1.0">
    <link rel="icon" type="image/x-icon" href="icon.png">

</head>
<body>  
    <nav>
    <div class="header-left">
            <a href="">
            <img src="logo.jpg" alt="SHOP. CO logo">
            </a>
        </div>
        <div class="header-center">
            <h1>Welcome to SHOP. CO</h1>
            <p>Find the best products here!</p>
        </div>
        <div class="header-right profile-menu">
            <button class="profile-button">My Profile</button>
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
                <a href="../cart/cart.php">My Cart</a>
                <a href="../kelola/barang.php">Product Manager</a>
                <a href="../register/register.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-product">
        <div class="product-category">
            <h2>Fashion</h2>
            <section class="product-list">
                <?php
                $products = [
                    ['name' => 'Baju Jirai Kei', 'price' => 335000, 'image' => '../foto/Baju Jirai Kei.jpeg'],
                    ['name' => 'Kaos Emyu', 'price' => 340000, 'image' => '../foto/Kaos Emyu.jpg'],
                    ['name' => 'Kaos Barca', 'price' => 240000, 'image' => '../foto/Kaos Barca.jpg'],
                    ['name' => 'Baju Pramuka', 'price' => 128000, 'image' => '../foto/Baju Pramuka.jpg'],
                    ['name' => 'Celana Merah', 'price' => 72000, 'image' => '../foto/Celana Merah.jpg']
                ];

                foreach ($products as $product) { 
                    echo "<div class='product-item'>"; 
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>"; 
                    echo "<h3>{$product['name']}</h3>"; 
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>"; 
                    echo "<form method='POST'>"; 
                    echo "<input type='hidden' name='product_name' value='{$product['name']}'>"; 
                    echo "<input type='hidden' name='product_price' value='{$product['price']}'>"; 
                    echo "<input type='hidden' name='product_image' value='{$product['image']}'>"; 
                    echo "<button type='submit' name='add_to_cart' class='myButton'>Add to Cart</button>"; 
                    echo "</form>"; 
                    echo "</div>"; 
                }
                ?>
            </section>
        </div>
    </div>

    <div class="container-product">
        <div class="product-category">
            <h2>Cosmetic</h2>
            <section class="product-list">
                <?php
                $products = [
                    ['name' => 'Masker ', 'price' => 86900, 'image' => '../foto/Masker.jpg'],
                    ['name' => 'Skincare', 'price' => 433000, 'image' => '../foto/Skincare.jpg'],
                    ['name' => 'Lipstick', 'price' => 83000, 'image' => '../foto/Lipstick.jpg'],
                    ['name' => 'Cusion', 'price' => 90000, 'image' => '../foto/Cusion.jpg'],
                    ['name' => 'Lipbalm', 'price' => 38000, 'image' => '../foto/Lipbalm.jpg']
                ];

                foreach ($products as $product) { 
                    echo "<div class='product-item'>"; 
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>"; 
                    echo "<h3>{$product['name']}</h3>"; 
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>"; 
                    echo "<form method='POST'>"; 
                    echo "<input type='hidden' name='product_name' value='{$product['name']}'>"; 
                    echo "<input type='hidden' name='product_price' value='{$product['price']}'>"; 
                    echo "<input type='hidden' name='product_image' value='{$product['image']}'>"; 
                    echo "<button type='submit' name='add_to_cart' class='myButton'>Add to Cart</button>"; 
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
                    ['name' => 'Figure Naruto', 'price' => 300000, 'image' => '../foto/Figure Naruto.jpg'],
                    ['name' => 'Jaket Kiana', 'price' => 255000, 'image' => '../foto/Jaket Kiana.jpg'],
                    ['name' => 'Pedang Anime', 'price' => 260000, 'image' => '../foto/Pedang Anime.jpg'],
                    ['name' => 'Hoodie Anime', 'price' => 332000, 'image' => '../foto/Hoodie Anime.jpg'],
                    ['name' => 'Gelang Elysia', 'price' => 109000, 'image' => '../foto/Gelang Elysia.jpg']
                ];

                foreach ($products as $product) { 
                    echo "<div class='product-item'>"; 
                    echo "<img src='{$product['image']}' alt='{$product['name']}'>"; 
                    echo "<h3>{$product['name']}</h3>"; 
                    echo "<p>Rp " . number_format($product['price'], 0, ',', '.') . "</p>"; 
                    echo "<form method='POST'>"; 
                    echo "<input type='hidden' name='product_name' value='{$product['name']}'>"; 
                    echo "<input type='hidden' name='product_price' value='{$product['price']}'>"; 
                    echo "<input type='hidden' name='product_image' value='{$product['image']}'>"; 
                    echo "<button type='submit' name='add_to_cart' class='myButton'>Add to Cart</button>"; 
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