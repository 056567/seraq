<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['total_harga'])) {
    include 'proses_pesanan.php';
    $metode = 'QRIS';
    $total = htmlspecialchars($_POST['total_harga']);
} else {
    header("Location: 2_pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pembayaran QRIS</title>
  <style>
    body {
      background-color: #001f3f;
      color: white;
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .box {
      background: #003366;
      padding: 30px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .qr-img {
      width: 200px;
      height: 200px;
      background: white;
      margin: 20px auto;
    }
    .btn-kembali {
      margin-top: 20px;
      background-color: #00bfff;
      padding: 10px 16px;
      border: none;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Pembayaran dengan QRIS</h2>
    <p>Total yang harus dibayar: <strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></p>
    <div class="qr-img">
      <!-- Nanti ganti dengan gambar QRIS asli -->
      <p style="color:black; line-height: 200px;">[QRIS]</p>
    </div>
    <p>Silakan scan QRIS di atas untuk melanjutkan pembayaran.</p>
    <a class="btn-kembali" href="2_pelanggan.php">Kembali ke Beranda</a>
  </div>
</body>
</html>