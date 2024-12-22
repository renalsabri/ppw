<?php
$price = isset($_GET['price']) ? intval($_GET['price']) : 0;
?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SHOP.CO | Checkout</title>
  <link rel="stylesheet" href="style_checkout.css">
</head>
<body>
  <div class="checkout-container">
    <span class="close">&times;</span>
    <div class="progress-bar">
      <div class="step active" id="step1Indicator">1</div>
      <div class="step" id="step2Indicator">2</div>
      <div class="step" id="step3Indicator">3</div>
    </div>
    <h2 class="total-price">Total Harga: Rp<span id="totalPrice"><?= number_format($price, 0, ',', '.') ?></span></h2>

    <!-- Form Langkah 1 -->
    <form id="step1Form">
      <div class="form-group">
        <input type="text" placeholder="Nomor Handphone" required>
        <input type="text" placeholder="Alamat" required>
      </div>
      <div class="payment-method">
        <div class="payment-header">
          <label>Pilih Metode Pembayaran:</label>
        </div>
        <div class="payment-options">
          <label class="payment-option">
            <input type="radio" name="paymentMethod" id="creditCard" value="Credit Card" required>
            <span class="payment-icon">&#128179;</span>
            <span>Kartu Kredit atau Debit</span>
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
      <button type="submit" class="submit-btn" id="step1Next">Lanjutkan Pembayaran</button>
    </form>

    <!-- Form Langkah 2 -->
    <form id="step2Form" style="display: none;">
      <div class="payment-details">
        <div id="creditCardForm" class="form-group" style="display: none;">
          <label>Nama Pemegang Kartu:</label>
          <input type="text" name="cardName" required>
          <label>Nomor Kartu Kredit:</label>
          <input type="text" name="cardNumber" maxlength="16" required>
          <label>Tanggal Kedaluwarsa:</label>
          <input type="month" name="expiryDate" required>
          <label>CVV:</label>
          <input type="text" name="cvv" maxlength="3" required>
        </div>

        <div id="eWalletForm" class="form-group" style="display: none;">
          <label>Pilih Provider:</label>
          <select name="eWalletProvider" required>
            <option value="OVO">OVO</option>
            <option value="GoPay">GoPay</option>
            <option value="Dana">Dana</option>
            <option value="ShopeePay">ShopeePay</option>
          </select>
          <label>Nomor E-Wallet:</label>
          <input type="text" name="eWalletNumber" required>
        </div>

        <div id="bankTransferForm" class="form-group" style="display: none;">
          <label>Pilih Bank Tujuan:</label>
          <select name="bank" required>
            <option value="BCA">BCA</option>
            <option value="Mandiri">Mandiri</option>
            <option value="BNI">BNI</option>
            <option value="BRI">BRI</option>
          </select>
          <div class="form-group">
            <label for="bankAccount">Nomor Rekening/VA:</label>
            <input type="text" id="bankAccount" name="bankAccount" placeholder="Masukkan nomor rekening" required>
          </div>
        </div>
      </div>
      <button type="submit" class="submit-btn" id="step2Next">Konfirmasi Pembayaran</button>
    </form>

    <!-- Langkah 3 -->
    <div id="step3" style="display: none; text-align: center;">
      <h2>Pembayaran Berhasil</h2>
      <div style="margin: 20px auto; width: 100px; height: 100px; background-color: black; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
        <span style="color: white; font-size: 48px;">&#10003;</span>
      </div>
      <button class="submit-btn" onclick="completeCheckout()">Selesai</button>
    </div>
  </div>

  <script>
    // Fungsi untuk memvalidasi form
    // Fungsi untuk memvalidasi form berdasarkan metode pembayaran yang dipilih
    function validateForm(form) {
      // Cek metode pembayaran yang dipilih
      const selectedMethod = Array.from(paymentMethodInputs).find(input => input.checked);
      let visibleInputs = [];

      if (selectedMethod) {
        // Validasi kolom-kolom yang relevan berdasarkan metode pembayaran
        if (selectedMethod.value === 'Credit Card') {
          visibleInputs = form.querySelectorAll('#creditCardForm input[required]');
        } else if (selectedMethod.value === 'E-Wallet') {
          visibleInputs = form.querySelectorAll('#eWalletForm input[required], #eWalletForm select[required]');
        } else if (selectedMethod.value === 'Bank Transfer') {
          visibleInputs = form.querySelectorAll('#bankTransferForm input[required], #bankTransferForm select[required]');
        }
      }

      // Validasi kolom yang terlihat (terkait dengan metode pembayaran yang dipilih)
      for (let input of visibleInputs) {
        if (!input.value.trim()) {
          return false;
        }
      }
      return true;
    }
    // Navigasi antar langkah
    const step1Form = document.getElementById('step1Form');
    const step2Form = document.getElementById('step2Form');
    const step3 = document.getElementById('step3');
    const step1Next = document.getElementById('step1Next');
    const step2Next = document.getElementById('step2Next');
    const paymentMethodInputs = document.getElementsByName('paymentMethod');

    const creditCardForm = document.getElementById('creditCardForm');
    const eWalletForm = document.getElementById('eWalletForm');
    const bankTransferForm = document.getElementById('bankTransferForm');

    step1Next.addEventListener('click', (e) => {
      e.preventDefault(); // Mencegah form untuk submit

      const selectedMethod = Array.from(paymentMethodInputs).find(input => input.checked);
      if (!selectedMethod) {
        alert('Pilih metode pembayaran terlebih dahulu.');
        return;
      }

      // Validasi form langkah 1
      if (!validateForm(step1Form)) {
        alert('Semua kolom di Langkah 1 harus diisi.');
        return;
      }

      // Pindah ke langkah 2
      step1Form.style.display = 'none';
      step2Form.style.display = 'block';

      document.getElementById('step1Indicator').classList.add('active');
      document.getElementById('step2Indicator').classList.add('active');

      // Tampilkan formulir spesifik berdasarkan metode pembayaran
      if (selectedMethod.value === 'Credit Card') {
        creditCardForm.style.display = 'block';
      } else if (selectedMethod.value === 'E-Wallet') {
        eWalletForm.style.display = 'block';
      } else if (selectedMethod.value === 'Bank Transfer') {
        bankTransferForm.style.display = 'block';
      }
    });

    step2Next.addEventListener('click', (e) => {
      e.preventDefault();

      // Validasi form langkah 2
      if (!validateForm(step2Form)) {
        alert('Semua kolom di Langkah 2 harus diisi.');
        return;
      }

      // Pindah ke langkah 3
      step2Form.style.display = 'none';
      step3.style.display = 'block';

      // Perbarui indikator langkah
      document.getElementById('step2Indicator').classList.add('active');
      document.getElementById('step3Indicator').classList.add('active');
      document.querySelector('.total-price').style.display = 'none';
    });

    function completeCheckout() {
      document.getElementById('checkoutContainer').style.display = 'none';
      // Tambahkan logika akhir di sini jika diperlukan
    }

    // Tutup modal
    document.querySelector('.close').addEventListener('click', function () {
      window.parent.postMessage('closeCheckout', '*');
    });
  </script>
</body>
</html>