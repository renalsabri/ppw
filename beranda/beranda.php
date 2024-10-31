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
    <title>Beranda | E-commerce</title>
    <link rel="stylesheet" href="style_beranda.css?v=1.0">
    <link rel="icon" type="image/x-icon" href="icon.png">

</head>
<body>  
    <nav>
    <div class="header-left">
            <a href="">
            <img src="logo.jpg" alt="e-commerce logo">
            </a>
        </div>
        <div class="header-center">
            <h1>Welcome to SHOP. CO</h1>
            <p>Find the best products here!</p>
        </div>
        <div class="header-right profile-menu">
            <img src="user-profile.jpg" alt="Profile Picture" class="profile-pic">
            <span class="profile-text">My Profile</span>
            <div class="profile-dropdown">
                <p class="username">Username</p>
                <label for="upload-photo" class="upload-photo-btn">Unggah Foto Profil</label>
                <input type="file" id="upload-photo" class="file-input" style="display: none;">
                <a href="../cart/cart.php">My Cart</a>
                <a href="../login/login.php">Logout</a>
            </div>
        </div>
    </nav>

    <section class="product-list">
        <?php
        $products = [
            ['name' => 'HoVoid', 'price' => 100000, 'image' => 'boid.jpg'],
            ['name' => 'HoFlash', 'price' => 150000, 'image' => './produk2.jpg'],
            ['name' => 'Elysia Bracelet', 'price' => 200000, 'image' => './produk3.jpg']
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
            echo "<button type='submit' name='add_to_cart' class='myButton'>Tambah ke Keranjang</button>"; 
            echo "</form>"; 
            echo "</div>"; 
        }
        ?>
    </section>

    <section class="product-list">
        <?php
        $products = [
            ['name' => 'HoVoid', 'price' => 100000, 'image' => 'produk1.jpg'],
            ['name' => 'HoFlash', 'price' => 150000, 'image' => 'produk2.jpg'],
            ['name' => 'Elysia Bracelet', 'price' => 200000, 'image' => 'produk3.jpg']
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
            echo "<button type='submit' name='add_to_cart' class='myButton'>Tambah ke Keranjang</button>"; 
            echo "</form>"; 
            echo "</div>"; 
        }
        ?>
    </section>

    <section class="product-list">
        <?php
        $products = [
            ['name' => 'HoVoid', 'price' => 100000, 'image' => 'produk1.jpg'],
            ['name' => 'HoFlash', 'price' => 150000, 'image' => 'produk2.jpg'],
            ['name' => 'Elysia Bracelet', 'price' => 200000, 'image' => 'produk3.jpg']
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
            echo "<button type='submit' name='add_to_cart' class='myButton'>Tambah ke Keranjang</button>"; 
            echo "</form>"; 
            echo "</div>"; 
        }
        ?>
    </section>
    

    <footer>
        <p>© 2024 E-Commerce. All rights reserved.</p>
    </footer>
</body>
</html>