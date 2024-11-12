<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Item | SHOP.CO</title>
    <link rel="stylesheet" href="style_kelola.css">
    <link rel="icon" type="image/x-icon" href="image/icon.png">
</head>
<body>
<nav>
    <div class="header-left">
            <a href="../beranda/beranda.php">
            <img src="image/logo copy.jpg" alt="SHOP. CO logo">
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
                        if (isset($_SESSION['nama'])) {
                            echo htmlspecialchars($_SESSION['nama']);
                        } else {
                            echo "Guest";
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
        <div class="container">
            <aside class="sidebar"></aside>
            <section class="content">
                <div class="content-header">
                    <h2></h2>
                    <a href="insert.php" class="btn-insert">Insert</a>
                </div>
                <h3>Data Barang</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Image</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><?php echo number_format($item['No']); ?></td>
                                <td><?php echo htmlspecialchars($item['Category']); ?></td>
                                <td><?php echo htmlspecialchars($item['Product Name']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($item['image']); ?>" width="50" alt="<?php echo htmlspecialchars($item['Product Name']); ?>"></td>
                                <td>Rp <?php echo number_format($item['Price'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No data found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <footer>
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">
                            Copyright © 2024
                            <a href="#" class="text-dark">SHOP.CO</a>
                        </li>
                    </ul>
                </footer>
            </section>
        </div>
    </div>
</body>
</html>