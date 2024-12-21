<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SHOP. CO | Checkout</title>
  <link rel="stylesheet" href="style_checkout.css">
</head>
<body>
  <div class="checkout-container">
    <span class="close">&times;</span>
    <div class="progress-bar">
      <div class="step active">1</div>
      <div class="step">2</div>
      <div class="step">3</div>
    </div>
    <h2 class="total-price">Total Harga: Rp<span id="totalPrice">900.000</span></h2>
    <form>
      <div class="form-group">
        <input type="text" placeholder="Nomor Handphone Pengirim" required>
        <input type="text" placeholder="Alamat untuk Pengiriman" required>
      </div>
      <div class="payment-method">
        <div class="payment-header">
          <label>Pilih Metode Pembayaran:</label>
        </div>
        <div class="payment-options">
          <label class="payment-option">
            <input type="radio" name="paymentMethod" id="creditCard" value="Credit Card" required>
            <span class="payment-icon">&#128179;</span>
            <span>Kartu kredit atau Debit</span>
          </label>
          <label class="payment-option">
            <input type="radio" name="paymentMethod" id="eWallet" value="E-Wallet">
            <span class="payment-icon">&#128184;</span>
            <span>E-Wallet</span>
          </label>
          <label class="payment-option">
            <input type="radio" name="paymentMethod" id="bankTransfer" value="Bank Transfer">
            <span class="payment-icon">&#128176;</span>
            <span>Transfer Bank</span>
          </label>
        </div>
      </div>
      <button type="submit" class="submit-btn">Lanjutkan Pembayaran</button>
    </form>
  </div>
  <script>
    // Kirim pesan untuk menutup modal ketika tombol close ditekan
    document.querySelector('.close').addEventListener('click', function () {
      window.parent.postMessage('closeCheckout', '*');
    });
  </script>
</body>
</html>