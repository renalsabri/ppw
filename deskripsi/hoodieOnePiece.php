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
// Menangani tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $size = $_POST['size'];
    $new_product_name = $product_name . " - " . $size;
    $new_product = [
        'name' => $new_product_name,
        'price' => $product_price,
        'image' => $product_image,
        'quantity' => 1,
        'size' => $size
    ];
    if (isset($_SESSION['cart'])) {
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $new_product_name) {
                $found = true;
                break;
            }
        }
        if ($found) {
            echo json_encode(['status' => 'error', 'message' => 'Produk dengan ukuran ini sudah ada di keranjang.']);
            exit();
        } else {
            $_SESSION['cart'][] = $new_product;
        }
    } else {
        $_SESSION['cart'] = [$new_product];
    }
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
    exit();
}
// Menangani tambah review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_text']) && isset($_POST['product_id']) && isset($_SESSION['email'])) {
    header('Content-Type: application/json');
    $user_email = $_SESSION['email'];
    $product_id = $_POST['product_id'];
    $review_text = $_POST['review_text'];
    $query = "INSERT INTO reviews (user_email, product_id, review_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sis', $user_email, $product_id, $review_text);
    if ($stmt->execute()) {
        $new_review_id = $conn->insert_id;
        $query_count = "SELECT COUNT(*) AS review_count FROM reviews WHERE product_id = ?";
        $stmt_count = $conn->prepare($query_count);
        $stmt_count->bind_param('i', $product_id);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $review_count = $result_count->fetch_assoc()['review_count'];
        $query_latest_review = "SELECT r.review_text, r.created_at, u.nama AS username, u.foto AS profile_picture 
                                FROM reviews r 
                                JOIN user u ON r.user_email = u.email 
                                WHERE r.id_reviews = ?";
        $stmt_latest = $conn->prepare($query_latest_review);
        $stmt_latest->bind_param('i', $new_review_id);
        $stmt_latest->execute();
        $latest_review = $stmt_latest->get_result()->fetch_assoc();
        $response = [
            'status' => 'success',
            'message' => 'Review submitted successfully!',
            'review_count' => $review_count,
            'reviews_html' => "
            <div class='review'>
                <img src='{$latest_review['profile_picture']}' alt='Profile Picture' class='profile-pic'>
                <div class='review-content'>
                    <strong>{$latest_review['username']}</strong>
                    <p>" . htmlspecialchars($latest_review['review_text']) . "</p>
                    <small>Just now</small>
                </div>
            </div>"
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add review.']);
    }
    exit();
}
// Ambil jumlah review produk
$product_id = $_GET['product_id'] ?? 14;
$query = "SELECT r.review_text, r.created_at, u.nama AS username, u.foto AS profile_picture 
          FROM reviews r 
          JOIN user u ON r.user_email = u.email 
          WHERE r.product_id = ? 
          ORDER BY r.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$reviews = $stmt->get_result();
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
    <title>SHOP. CO | Hoodie One Piece</title>
    <link rel="stylesheet" href="style_deskripsi.css?v=1.0">
    <link rel="icon" type="image/x-icon" href="../foto/icon.png">
</head>
<body>  
    <nav>
        <div class="header-left">
            <a href="../beranda/beranda.php">
            <img src="../foto/logo copy.jpg" alt="SHOP. CO logo">
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
                    if (isset($_SESSION['nama'])) {
                        echo htmlspecialchars($_SESSION['nama']);
                    } else {
                        echo "Guest";
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
    <div class="product-container">
        <div class="image-section">
            <img id="main-image" src="../foto/Hoodie One Piece.jpg" alt="Hoodie One Piece">
            <div class="thumbnail-section">
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../foto/Hoodie One Piece.jpg" alt="Hoodie One Piece" onclick="changeImage('../foto/Hoodie One Piece.jpg')">
                </div>
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../foto/Hoodie One Piece 2.jpg" alt="Hoodie One Piece" onclick="changeImage('../foto/Hoodie One Piece 2.jpg')">
                </div>
            </div>
        </div>
        <div class="details-section">
            <h1>Hoodie One Piece</h1>
            <div class="rating">★★★★★</div>
            <p class="price" id="product-price">Rp90.000</p>
            <p class="description">
                Hoodie ini terbuat dari bahan lembut dan nyaman, cocok untuk gaya santai sehari-hari. Jadikan hoodie ini pilihan sempurna bagi para penggemar One Piece!
            </p>
            <label for="size-select">Pilih Ukuran</label>
            <select id="size-select">
                <option value="" data-price="90000">Pilih Ukuran</option>
                <option value="M" data-price="90000">M - Rp90.000</option>
                <option value="L" data-price="90000">L - Rp90.000</option>
                <option value="XL" data-price="90000">XL - Rp90.000</option>
                <option value="XXL" data-price="90000">XXL - Rp90.000</option>
            </select>
            <form action="" id="product-form" method="POST">
                <input type="hidden" name="product_name" value="Hoodie One Piece">
                <input type="hidden" name="product_price" id="hidden-product-price" value="90000">
                <input type="hidden" name="product_image" value="../foto/Hoodie One Piece.jpg">
                <button type="button" class="buy-now">Beli Sekarang</button>
                <div id="paymentModal" class="modal">
                    <div class="modal-content">
                        <iframe src="../checkout/checkout.php" frameborder="0" id="checkoutFrame"></iframe>
                    </div>
                </div>
                <button type="submit" name="add_to_cart" class="add-to-cart">Tambah ke Keranjang</button>
            </form>
            <p class="meta">
                <strong>Kategori:</strong> Pakaian Pria/Wanita, Hoodie, Merchandise Anime, Pakaian Santai<br>
                <strong>Tagar:</strong> One Piece, Monkey D Luffy, Hoodie Anime, Merchandise One Piece, Anime Fashion
            </p>
        </div>
    </div>
    <!-- Tab Deskripsi dan Ulasan -->
    <div class="tabs">
        <button class="tab-button active" onclick="showTab('description')">Tentang Produk</button>
        <button class="tab-button" onclick="showTab('reviews')">Ulasan</button>
    </div>
    <div class="tab-content" id="description">
        <h1 class="product-title">Hoodie One Piece Monkey D. Luffy - Gaya Keren untuk Para Penggemar Anime!</h1>
        <p class="product-description">
            Tampil stylish dan nyaman dengan <strong>Hoodie One Piece Monkey D. Luffy</strong>, hoodie yang terinspirasi dari karakter utama One Piece, Monkey D. Luffy. Dengan desain yang menarik dan bahan berkualitas, hoodie ini sempurna untuk melengkapi penampilan santai Anda, baik di rumah, berkumpul bersama teman, maupun beraktivitas sehari-hari.
        </p>
        <h3>Fitur Utama:</h3>
        <ul class="features-list">
            <li><strong>Bahan Berkualitas</strong>: Terbuat dari bahan katun yang lembut dan nyaman, menjaga tubuh tetap hangat namun tetap ringan dipakai.</li>
            <li><strong>Desain Modern dan Casual</strong>: Potongan hoodie yang stylish dan cocok untuk berbagai kegiatan kasual.</li>
            <li><strong>Serbaguna</strong>: Dapat digunakan di berbagai kesempatan, mulai dari nonton anime, hangout, hingga olahraga ringan.</li>
        </ul>
        <h3>Spesifikasi Tambahan:</h3>
        <ul class="features-list">
            <li><strong>Bahan:</strong> 100% Katun (Lembut dan Nyaman)</li>
            <li><strong>Tabel Ukuran & Cara Ukur:</strong> Silakan cek foto produk ke-2 untuk ukuran yang tersedia.</li>
            <li><strong>Catatan:</strong> Pastikan memilih ukuran yang sesuai dengan petunjuk ukuran yang tertera untuk kenyamanan maksimal.</li>
        </ul>
        <h3>Kenapa Harus Membeli?</h3>
        <ul class="features-list">
            <li><strong>Kenyamanan Sepanjang Hari</strong>: Bahan katun yang lembut dan tidak panas membuat hoodie ini nyaman dipakai seharian.</li>
            <li><strong>Tahan Lama</strong>: Dengan bahan berkualitas dan perawatan yang mudah, hoodie ini akan bertahan lama dan tetap nyaman dipakai.</li>
        </ul>
        <h3>Tips Perawatan:</h3>
        <ul class="features-list">
            <li>Cuci dengan air dingin untuk menjaga kualitas bahan dan warna tetap cerah.</li>
            <li>Hindari penggunaan pemutih dan jemur di tempat teduh agar hoodie tetap awet dan nyaman dipakai.</li>
        </ul>
    </div>
    <div class="tab-content" id="reviews" style="display: none;">
        <h3>Ulasan (<?= $review_count ?>)</h3>
        <?php if (isset($_SESSION['email'])): ?>
            <form id="review-form" action="/ppw/deskripsi/hoodieOnePiece.php?product_id=<?= $product_id ?>" method="POST">
                <textarea name="review_text" rows="4" placeholder="Ketik Ulasanmu..." required></textarea>
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <button type="submit">Kirim Ulasan</button>
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
        const sizeSelect = document.getElementById('size-select');
        const productPrice = document.getElementById('product-price');
        const hiddenPrice = document.getElementById('hidden-product-price');
        const buyNowButton = document.querySelector('.buy-now');
        const checkoutFrame = document.getElementById('checkoutFrame');
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
        document.querySelector('.buy-now').addEventListener('click', function () {
            const modal = document.getElementById('paymentModal');
            modal.style.display = 'block';
        });
        sizeSelect.addEventListener('change', function () {
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            productPrice.textContent = 'Rp' + parseInt(price).toLocaleString('id-ID');
            hiddenPrice.value = price;
        });
        buyNowButton.addEventListener('click', function () {
            const sizeValue = sizeSelect.value;
            const modal = document.getElementById('paymentModal');
            if (!sizeValue) {
                alert('Silakan pilih ukuran terlebih dahulu!');
                modal.style.display = 'none';
                return;
            } else {
                const price = hiddenPrice.value;
                const checkoutUrl = `../checkout/checkout.php?price=${price}`;
                checkoutFrame.src = checkoutUrl;
                modal.style.display = 'block';
            }
        });
        window.addEventListener('message', function (event) {
            if (event.data === 'closeCheckout') {
                const modal = document.getElementById('paymentModal');
                modal.style.display = 'none';
            }
            if (event.data === 'checkoutComplete') {
                const modal = document.getElementById('paymentModal');
                modal.style.display = 'none';

                window.location.href = 'hoodieOnePiece.php';
            }
        });
        $(document).ready(function () {
            $("#review-form").on("submit", function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr("action"),
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        console.log("Raw response: ", response);
                        try {
                            if (response.status === "success") {
                                $("h3").text(`Ulasan (${response.review_count})`);
                                $("#reviews-list").prepend(response.reviews_html);
                                $("textarea[name='review_text']").val("");
                            } else {
                                alert(response.message);
                            }
                        } catch (error) {
                            console.error("Error parsing response: ", error);
                            alert("Terjadi kesalahan dalam memproses ulasan.");
                        }
                    },
                    error: function () {
                        alert("Terjadi kesalahan saat mengirim ulasan.");
                    }
                });
            });
            $(document).ready(function () {
                $(".add-to-cart").on("click", function (e) {
                    e.preventDefault();
                    const product_name = $("input[name='product_name']").val();
                    const product_price = $("#hidden-product-price").val();
                    const product_image = $("input[name='product_image']").val();
                    const selected_size = $("#size-select").val();
                    if (!selected_size) {
                        alert("Pilih ukuran terlebih dahulu!");
                        return;
                    }
                    $.ajax({
                        url: "hoodieOnePiece.php",
                        type: "POST",
                        data: {
                            add_to_cart: true,
                            product_name: product_name,
                            product_price: product_price,
                            product_image: product_image,
                            size: selected_size
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                alert(data.message);
                            }
                            if (data.status === 'error') {
                                alert(data.message);
                            }
                        },
                        error: function() {
                            alert("Terjadi kesalahan saat menambahkan produk ke keranjang.");
                        }
                    });
                });
            });
            $("#size-select").on("change", function () {
                const selectedOption = $(this).find(":selected");
                const selectedPrice = selectedOption.data("price");
                const priceElement = $("#product-price");
                if (selectedPrice) {
                    priceElement.text(`Rp${selectedPrice.toLocaleString("id-ID")}`);
                } else {
                    priceElement.text("Rp0");
                }
                $("#hidden-product-price").val(selectedPrice || 0);
            });
        });
    </script>
</body>
</html>