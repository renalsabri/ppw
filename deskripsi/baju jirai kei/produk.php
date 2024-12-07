<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

if (isset($_SESSION['name']) && isset($_SESSION['foto']) && isset($_SESSION['email'])) {
    $name = $_SESSION['name'];
    $foto = $_SESSION['foto'];
    $email = $_SESSION['email'];  // Email pengguna yang login
} else {
    $name = "Guest";
    $foto = 'default_profile_picture.png';  // Set foto default
}

$product_id = $_GET['product_id'] ?? 1;  // Default produk ID 1 jika tidak ada di URL

// Menangani pengiriman review oleh pengguna yang sudah login via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['email'])) {
    // Check if it's an AJAX request
    if (isset($_POST['review_text']) && isset($_POST['product_id'])) {
        $user_email = $_SESSION['email'];  // Mengambil email pengguna
        $product_id = $_POST['product_id'];  // ID produk
        $review_text = $_POST['review_text'];  // Teks review

        // Menambahkan review ke database
        $query = "INSERT INTO reviews (user_email, product_id, review_text) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sis', $user_email, $product_id, $review_text);  // 's' untuk email, 'i' untuk product_id, 's' untuk review_text

        if ($stmt->execute()) {
            // Mengambil data terbaru untuk review
            $query_reviews = "SELECT r.review_text, r.created_at, u.nama AS username, u.foto AS profile_picture 
                              FROM reviews r 
                              JOIN user u ON r.user_email = u.email 
                              WHERE r.product_id = ? 
                              ORDER BY r.created_at DESC";
            $stmt_reviews = $conn->prepare($query_reviews);
            $stmt_reviews->bind_param('i', $product_id);
            $stmt_reviews->execute();
            $reviews = $stmt_reviews->get_result();

            // Mengambil jumlah review terbaru
            $query_count = "SELECT COUNT(*) AS review_count FROM reviews WHERE product_id = ?";
            $stmt_count = $conn->prepare($query_count);
            $stmt_count->bind_param('i', $product_id);
            $stmt_count->execute();
            $result_count = $stmt_count->get_result();
            $review_count = $result_count->fetch_assoc()['review_count'];

            // Menyusun response untuk AJAX
            $reviews_html = '';
            while ($review = $reviews->fetch_assoc()) {
                $reviews_html .= '
                    <div class="review">
                        <img src="' . $review['profile_picture'] . '" alt="Profile Picture" class="profile-pic">
                        <div class="review-content">
                            <strong>' . htmlspecialchars($review['username']) . '</strong>
                            <p>' . htmlspecialchars($review['review_text']) . '</p>
                            <small>' . $review['created_at'] . '</small>
                        </div>
                    </div>';
            }

            // Response untuk AJAX: jumlah review dan daftar review
            echo json_encode([
                'review_count' => $review_count,
                'reviews_html' => $reviews_html
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add review']);
        }
    }
    exit(); // Stop further execution
}

// Query untuk mengambil semua review produk dan jumlah review
$query = "SELECT r.review_text, r.created_at, u.nama AS username, u.foto AS profile_picture 
          FROM reviews r 
          JOIN user u ON r.user_email = u.email 
          WHERE r.product_id = ? 
          ORDER BY r.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$reviews = $stmt->get_result();

// Query untuk mendapatkan jumlah review
$query_count = "SELECT COUNT(*) AS review_count FROM reviews WHERE product_id = ?";
$stmt_count = $conn->prepare($query_count);
$stmt_count->bind_param('i', $product_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$review_count = $result_count->fetch_assoc()['review_count'];
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP. CO | Product Details</title>
    <link rel="stylesheet" href="style_deskripsi.css?v=1.0">
    <link rel="icon" type="image/x-icon" href="..foto/icon.png">
</head>
<body>  
    <!-- Header dan Navigasi -->
    <nav>
        <div class="header-left">
            <a href="../../beranda/beranda.php">
            <img src="../../foto/logo copy.jpg" alt="SHOP. CO logo">
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
                        echo htmlspecialchars($_SESSION['nama']);
                    } else {
                        echo "Guest";
                    }
                    ?>
                    </p>
                </center>
            </div>
                <a href="../../cart/cart.php">My Cart</a>
                <a href="../../kelola/barang.php">Product Manager</a>
                <a href="../../register/register.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Halaman Produk -->
    <div class="product-container">
        <div class="image-section">
            <img id="main-image" src="../../foto/Baju Jirai Kei.jpeg" alt="Baju Jirai Kei">
            <div class="thumbnail-section">
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../../foto/Baju Jirai Kei.jpeg" alt="Baju Jirai Kei" onclick="changeImage('../../foto/Baju Jirai Kei.jpeg')">
                </div>
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../../foto/Baju Jirai Kei 2.png" alt="Baju Jirai Kei" onclick="changeImage('../../foto/Baju Jirai Kei 2.png')">
                </div>
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../../foto/Baju Jirai Kei 3.png" alt="Baju Jirai Kei" onclick="changeImage('../../foto/Baju Jirai Kei 3.png')">
                </div>
            </div>
        </div>
        <div class="details-section">
            <h1>Baju Jirai Kei</h1>
            <div class="rating">★★★★★</div>
            <p class="price">Rp335.000</p>
            <p class="description">
                Dibuat dengan bahan berkualitas, nyaman dipakai sepanjang hari, dan cocok untuk berbagai acara. Tersedia dalam berbagai ukuran dan pilihan warna menarik.
            </p>
            <label for="size-select">Select Size</label>
            <select id="size-select">
                <option value="">Select Size</option>
                <option value="s">Small</option>
                <option value="m">Medium</option>
                <option value="l">Large</option>
            </select>

            <!-- Form untuk Menambahkan ke Keranjang -->
            <form action="product.php" method="POST">
                <input type="hidden" name="product_name" value="Baju Jirai Kei">
                <input type="hidden" name="product_price" value="335000">
                <input type="hidden" name="product_image" value="../../foto/Baju Jirai Kei.jpeg">
                <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
            </form>

            <p class="meta">
                <strong>Category:</strong> Wanita, Pakaian Atasan, Gaya Jepang, Kasual<br>
                <strong>Tags:</strong> Jirai Kei, Baju Gaya Jepang, Tren Fashion Jepang, Baju Kasual Modern
            </p>
        </div>
    </div>

    <!-- Tab Deskripsi dan Ulasan -->
    <div class="tabs">
        <button class="tab-button active" onclick="showTab('description')">Description</button>
        <button class="tab-button" onclick="showTab('reviews')">Reviews</button>
    </div>
    <div class="tab-content" id="description">
        <h1 class="product-title">Baju Jirai Kei - Gaya Unik dan Futuristik dengan Sentuhan Jepang!</h1>
        <p class="product-description">
            Tampil beda dengan <strong>Baju Jirai Kei</strong>, koleksi fashion terbaru yang terinspirasi dari gaya futuristik khas Jepang yang menggabungkan elemen tradisional dengan sentuhan modern. Baju ini cocok untuk Anda yang ingin tampil stylish dan mencuri perhatian dalam setiap kesempatan. Dengan desain yang unik dan inovatif, baju Jirai Kei memberikan kenyamanan sekaligus keanggunan yang tak tertandingi.
        </p>
        <h3>Fitur Utama:</h3>
        <ul class="features-list">
            <li><strong>Desain Futuristik</strong>: Mengusung konsep Jirai Kei, baju ini memiliki potongan dan detail yang menggabungkan teknologi dan budaya Jepang.</li>
            <li><strong>Material Berkualitas</strong>: Terbuat dari bahan pilihan yang lembut, nyaman, dan tahan lama, cocok dipakai sehari-hari ataupun untuk acara khusus.</li>
            <li><strong>Tersedia dalam Berbagai Ukuran</strong>: Dapatkan baju ini dalam berbagai pilihan ukuran, dari S hingga XL, yang bisa disesuaikan dengan kebutuhan Anda.</li>
            <li><strong>Warna dan Motif Menarik</strong>: Memiliki berbagai pilihan warna dan motif yang menarik, mulai dari warna netral hingga warna-warna cerah yang energik, memberikan kesan modern dan edgy.</li>
            <li><strong>Praktis dan Serbaguna</strong>: Mudah dipadupadankan dengan berbagai jenis celana, rok, atau aksesori lain untuk menciptakan gaya yang lebih personal.</li>
        </ul>
        <h3>Kenapa Harus Membeli?</h3>
        <ul class="features-list">
            <li><strong>Gaya Unik</strong>: Ciptakan kesan modern dan futuristik dengan sentuhan budaya Jepang yang kuat.</li>
            <li><strong>Kenyamanan Sepanjang Hari</strong>: Dibuat dengan bahan yang nyaman, Anda bisa mengenakan baju ini sepanjang hari tanpa merasa tidak nyaman.</li>
            <li><strong>Tahan Lama</strong>: Bahan berkualitas tinggi yang dijamin awet dan mudah dirawat, sehingga Anda bisa tampil modis dalam waktu lama.</li>
        </ul>
        <h3>Tips Perawatan:</h3>
        <ul class="features-list">
            <li>Cuci dengan air dingin dan gunakan deterjen ringan untuk menjaga kualitas bahan tetap terjaga.</li>
            <li>Jangan gunakan pemutih atau mesin pengering, cukup jemur di tempat yang teduh.</li>
        </ul>
    </div>

    <div class="tab-content" id="reviews" style="display: none;">
        <h3>Reviews (<?= $review_count ?>)</h3>  <!-- Menampilkan jumlah review -->
        <?php if (isset($_SESSION['email'])): ?>  <!-- Hanya tampilkan form jika pengguna sudah login -->
            <form id="review-form" action="product.php?product_id=<?= $product_id ?>" method="POST">
                <textarea name="review_text" rows="4" placeholder="Write your review..." required></textarea>
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <button type="submit">Submit Review</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Login</a> to add a review.</p>
        <?php endif; ?>

        <div class="reviews" id="reviews-list">
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review">
                    <img src="<?= $review['profile_picture'] ?>" alt="Profile Picture" class="profile-pic">
                    <div class="review-content">
                        <strong><?= htmlspecialchars($review['username']) ?></strong>
                        <p><?= htmlspecialchars($review['review_text']) ?></p>
                        <small><?= $review['created_at'] ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <p>© 2024 SHOP.CO. All rights reserved.</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function changeImage(imageSrc) {
            const mainImage = document.getElementById('main-image');
            mainImage.classList.add('fade-out');
            setTimeout(() => {
                mainImage.src = imageSrc;
                mainImage.classList.remove('fade-out');
                mainImage.classList.add('fade-in');
                setTimeout(() => mainImage.classList.remove('fade-in'), 300);
            }, 300);
        }

        function showTab(tabName) {
            const descriptionTab = document.getElementById('description');
            const reviewsTab = document.getElementById('reviews');
            const descriptionButton = document.querySelector('.tab-button:nth-child(1)');
            const reviewsButton = document.querySelector('.tab-button:nth-child(2)');
            
            descriptionTab.style.display = 'none';
            reviewsTab.style.display = 'none';
            descriptionButton.classList.remove('active');
            reviewsButton.classList.remove('active');
            
            if (tabName === 'description') {
                descriptionTab.style.display = 'block';
                descriptionButton.classList.add('active');
            } else if (tabName === 'reviews') {
                reviewsTab.style.display = 'block';
                reviewsButton.classList.add('active');
            }
        }

        $(document).ready(function(){
            $("#review-form").on("submit", function(event){
                event.preventDefault();  // Mencegah form dari submit biasa

                var reviewText = $("textarea[name='review_text']").val();
                var productId = $("input[name='product_id']").val();

                $.ajax({
                    url: "product.php?product_id=" + productId,
                    type: "POST",
                    data: {
                        review_text: reviewText,
                        product_id: productId
                    },
                    success: function(response) {
                        console.log('Success:', response);
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            // Update jumlah review
                            $("h3").text("Reviews (" + data.review_count + ")");
                            // Tambahkan review baru ke dalam daftar review
                            $("#reviews-list").prepend(data.reviews_html);
                            // Kosongkan textarea
                            $("textarea[name='review_text']").val('');
                        } else {
                            alert(data.message);  // Tampilkan error jika ada masalah
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            });
        });
    </script>
</body>
</html>