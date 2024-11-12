<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $kategori = $_POST['kategori'] ?? '';
    $pakaian = $_POST['pakaian'] ?? '';
    $harga = $_POST['harga'] ?? '';

    // Memastikan folder untuk menyimpan gambar ada
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
    }

    // Ambil informasi file gambar
    $gambar = $_FILES['gambar']['name'];
    $tmpGambar = $_FILES['gambar']['tmp_name'];
    $uploadFilePath = $uploadDir . basename($gambar);

    // Simpan data barang ke dalam sesi
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Tambahkan item baru ke sesi
    $_SESSION['cart'][] = [
        'No' => count($_SESSION['cart']) + 1, // Nomor barang
        'Category' => $kategori,
        'Product Name' => $pakaian,
        'image' => $uploadFilePath, // Menyimpan jalur gambar
        'Price' => floatval(str_replace(',', '', $harga)) // Konversi harga ke float
    ];

    // Jika gambar diupload, pindahkan file ke folder yang diinginkan
    if ($gambar) {
        move_uploaded_file($tmpGambar, $uploadFilePath); // Pindahkan file gambar ke folder uploads
    }

    // Redirect ke halaman barang setelah menyimpan
    header("Location: barang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager | SHOP.CO</title>
    <link rel="stylesheet" href="style_kelola.css">
    <link rel="icon" type="image/x-icon" href="image/icon.png">
</head>
<body>
    <div class="page">
    <nav>
    <div class="header-left">
            <a href="../beranda/beranda.php">
            <img src="logo copy.jpg" alt="SHOP. CO logo">
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
        <div class="container">
            <aside class="sidebar">
                <h3></h3>
                <ul>
                    <li><a href="barang.php">Barang</a></li>
                </ul>
            </aside>
            <section class="content">
                <div class="content-header">
                    <h2></h2>
                    <a href="barang.php" class="btn-insert">Insert</a>
                </div>
                <div class="insert-form">
                    <h3>Insert Barang</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <label>Kategori</label>
                        <input type="text" name="kategori" placeholder="Masukkan kategori" required>
                        <label>Barang</label>
                        <input type="text" name="pakaian" placeholder="Masukkan nama pakaian" required>
                        <label>Gambar</label>
                        <input type="file" name="gambar" accept="image/*" required>
                        <label>Harga</label>
                        <input type="text" name="harga" placeholder="Masukkan harga" required>
                        <button type="submit" class="btn-submit">Simpan</button>
                    </form>
                </div>
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