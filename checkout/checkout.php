<?php
// Ambil harga dari session atau POST
$product_price = $_SESSION['checkout']['price'] ?? 0; // Jika tidak ada di sesi, default ke 0
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SHOP. CO | Checkout</title>
  <link rel="stylesheet" href="style_checkout.css?v=1.0">
</head>
<body>
  <div class="popup-overlay" id="checkoutPopup">
    <div class="popup-content">
      <button class="close-btn" onclick="closePopup()">×</button>
      <h2>Pembayaran</h2>
      <div class="steps">
        <span class="step active">1</span>
        <span class="step">2</span>
        <span class="step">3</span>
      </div>
      <div class="popup-content">
        <h3>Harga Produk: Rp <?= number_format($product_price, 0, ',', '.') ?></h3>
        <!-- Form pembayaran lainnya -->
      </div>
    </div>
  </div>

  <script>
    function closePopup() {
      document.getElementById("checkoutPopup").style.display = "none";
    }
  </script>
</body>
</html>

?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SHOP. CO | Checkout</title>
  <link rel="stylesheet" href="style_checkout.css?v=1.0">
</head>
<body>
  <!-- Popup Content -->
  <div class="popup-overlay" id="checkoutPopup">
    <div class="popup-content">
      <button class="close-btn" onclick="closePopup()">×</button>
      <h2>Pembayaran</h2>
      <div class="steps">
        <span class="step active">1</span>
        <span class="step">2</span>
        <span class="step">3</span>
      </div>
      <div class="popup-content">
        <h3>Harga Produk: Rp <?= number_format($product_price, 0, ',', '.') ?></h3>
        <form>
          <div class="form-group">
            <input type="text" placeholder="Nama Awal" required />
            <input type="text" placeholder="Nama Akhir" required />
          </div>
          <div class="form-group">
            <input type="email" placeholder="Alamat E-mail" required />
            <input type="text" placeholder="Alamat Pengiriman" required />
          </div>
          <div class="form-group">
            <label>Pilih Metode Pembayaran</label>
            <div class="payment-options">
              <label><input type="radio" name="payment" checked /> Credit Card atau Debit</label>
              <label><input type="radio" name="payment" /> E-Wallet</label>
              <label><input type="radio" name="payment" /> Transfer Bank</label>
            </div>
          </div>
          <button type="button" class="submit-btn">Lanjutkan Pembayaran</button>
        </form>
    </div>
  </div>

  <script>
    function closePopup() {
      document.getElementById("checkoutPopup").style.display = "none";
    }
  </script>
</body>
</html>