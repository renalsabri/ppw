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
$product_id = $_GET['product_id'] ?? 1;
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
    <title>SHOP. CO | Baju Jirai Kei</title>
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
                <a href="../cart/cart.php">Keranjang</a>
                <a href="../kelola/barang.php">Kelola Barang</a>
                <a href="../register/register.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="product-container">
        <div class="image-section">
            <img id="main-image" src="../foto/Baju Jirai Kei.jpeg" alt="Baju Jirai Kei">
            <div class="thumbnail-section">
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../foto/Baju Jirai Kei.jpeg" alt="Baju Jirai Kei" onclick="changeImage('../foto/Baju Jirai Kei.jpeg')">
                </div>
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../foto/Baju Jirai Kei 2.png" alt="Baju Jirai Kei" onclick="changeImage('../foto/Baju Jirai Kei 2.png')">
                </div>
                <div class="thumbnail-wrapper">
                    <img class="thumbnail" src="../foto/Baju Jirai Kei 3.png" alt="Baju Jirai Kei" onclick="changeImage('../foto/Baju Jirai Kei 3.png')">
                </div>
            </div>
        </div>
        <div class="details-section">
            <h1>Baju Jirai Kei</h1>
            <div class="rating">★★★★★</div>
            <p class="price" id="product-price">Rp335.000</p>
            <p class="description">
                Dibuat dengan bahan berkualitas, nyaman dipakai sepanjang hari, dan cocok untuk berbagai acara. Tersedia dalam berbagai ukuran dan pilihan warna menarik.
            </p>
            <label for="size-select">Pilih Ukuran</label>
            <select id="size-select">
                <option value="" data-price="335000">Pilih Ukuran</option>
                <option value="S" data-price="320000">S - Rp320.000</option>
                <option value="M" data-price="335000">M - Rp335.000</option>
                <option value="L" data-price="350000">L - Rp350.000</option>
            </select>

            <form action="" id="product-form" method="POST">
                <input type="hidden" name="product_name" value="Baju Jirai Kei">
                <input type="hidden" name="product_price" id="hidden-product-price" value="335000">
                <input type="hidden" name="product_image" value="../foto/Baju Jirai Kei.jpeg">
                <button type="button" class="buy-now">Beli Sekarang</button>
                <div id="paymentModal" class="modal">
                    <div class="modal-content">
                        <iframe src="../checkout/checkout.php" frameborder="0" id="checkoutFrame"></iframe>
                    </div>
                </div>
                <button type="submit" name="add_to_cart" class="add-to-cart">Tambah ke Keranjang</button>
            </form>

            <p class="meta">
                <strong>Kategori:</strong> Wanita, Pakaian Atasan, Gaya Jepang, Kasual<br>
                <strong>Tagar:</strong> Jirai Kei, Baju Gaya Jepang, Tren Fashion Jepang, Baju Kasual Modern
            </p>
        </div>
    </div>

    <!-- Tab Deskripsi dan Ulasan -->
    <div class="tabs">
        <button class="tab-button active" onclick="showTab('description')">Tentang Produk</button>
        <button class="tab-button" onclick="showTab('reviews')">Ulasan</button>
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
        <h3>Ulasan (<?= $review_count ?>)</h3>  <!-- Menampilkan jumlah review -->
        <?php if (isset($_SESSION['email'])): ?>  <!-- Hanya tampilkan form jika pengguna sudah login -->
            <form id="review-form" action="/ppw/deskripsi/bajuJiraiKei.php?product_id=<?= $product_id ?>" method="POST">
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

        // Menampilkan modal ketika tombol ditekan
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

        // Menutup modal ketika pesan "closeCheckout" diterima
        window.addEventListener('message', function (event) {
            if (event.data === 'closeCheckout') {
            const modal = document.getElementById('paymentModal');
            modal.style.display = 'none';
            }
        });

        $(document).ready(function () {
            $("#review-form").on("submit", function (e) {
                e.preventDefault(); // Mencegah refresh halaman
                
                const formData = $(this).serialize(); // Ambil data dari form

                $.ajax({
                    url: $(this).attr("action"), // URL endpoint dari form
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        console.log("Raw response: ", response); // Debugging
                        try {
                            if (response.status === "success") {
                                $("h3").text(`Ulasan (${response.review_count})`); // Update jumlah ulasan
                                $("#reviews-list").prepend(response.reviews_html); // Tambahkan review baru
                                $("textarea[name='review_text']").val(""); // Bersihkan textarea
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
                // Menangani event klik untuk tombol "Tambah ke Keranjang"
                $(".add-to-cart").on("click", function (e) {
                    e.preventDefault();  // Mencegah form submit default
                    
                    // Ambil data produk dari elemen terkait
                    const product_name = $("input[name='product_name']").val();
                    const product_price = $("#hidden-product-price").val();
                    const product_image = $("input[name='product_image']").val();
                    const selected_size = $("#size-select").val();

                    // Pastikan ukuran dipilih sebelum menambah ke keranjang
                    if (!selected_size) {
                        alert("Pilih ukuran terlebih dahulu!");
                        return;
                    }

                    // Kirim data menggunakan AJAX
                    $.ajax({
                        url: "bajuJiraiKei.php",  // URL yang sama (self-submission)
                        type: "POST",
                        data: {
                            add_to_cart: true,
                            product_name: product_name,
                            product_price: product_price,
                            product_image: product_image,
                            size: selected_size  // Kirim data ukuran
                        },
                        success: function(response) {
                            const data = JSON.parse(response);
                            
                            // Jika ada pesan sukses
                            if (data.status === 'success') {
                                alert(data.message);  // Menampilkan pesan sukses
                            }
                            
                            // Jika ada pesan error (produk sudah ada di keranjang)
                            if (data.status === 'error') {
                                alert(data.message);  // Menampilkan pesan error
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
                const selectedPrice = selectedOption.data("price"); // Ambil data harga berdasarkan ukuran

                // Update harga produk di halaman
                const priceElement = $("#product-price");
                if (selectedPrice) {
                    priceElement.text(`Rp${selectedPrice.toLocaleString("id-ID")}`);
                } else {
                    priceElement.text("Rp0");
                }

                // Update input hidden untuk harga
                $("#hidden-product-price").val(selectedPrice || 0);
            });
        });
    </script>
</body>
</html>