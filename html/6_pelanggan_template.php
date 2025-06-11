<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ringkasan Pesanan - SeraQ</title>
  <style>
    /* Gaya tetap sama dari HTML kamu sebelumnya */
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      background: linear-gradient(to bottom, #000d1a, #003366, #0055cc);
      color: white;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 30px 10px;
    }

    .container {
      background-color: #111a4c;
      width: 100%;
      max-width: 420px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
      padding: 24px 20px;
    }

    .title {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 20px;
      text-align: center;
    }

    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      border-bottom: 1px solid #444;
      padding-bottom: 10px;
    }

    .order-name {
      font-size: 16px;
    }

    .order-qty {
      font-size: 14px;
      color: #ccc;
    }

    .order-price {
      font-size: 16px;
      font-weight: bold;
    }

    .summary {
      margin-top: 25px;
      font-size: 16px;
    }

    .summary .label {
      color: #ccc;
    }

    .summary .value {
      font-weight: bold;
      color: #00ffcc;
    }

    .status-toggle {
      margin-top: 30px;
      text-align: center;
    }

    .btn-status {
      background-color: #00ccff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-status:hover {
      background-color: #009acd;
    }

    .status-box {
      margin-top: 20px;
      display: none;
      animation: fadeIn 0.5s ease-in-out;
    }

    .step {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .circle {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background-color: #555;
      margin-right: 12px;
      flex-shrink: 0;
    }

    .step.active .circle {
      background-color: #00ccff;
    }

    .step.done .circle {
      background-color: #00ffcc;
    }

    .step-label {
      font-size: 15px;
      color: #ccc;
    }

    .step.active .step-label {
      color: white;
      font-weight: bold;
    }

    .step.done .step-label {
      color: #00ffcc;
      font-weight: bold;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    .menu-container {
      position: absolute;
      top: 16px;
      right: 20px;
      z-index: 1000;
    }

    .menu-container details {
      position: relative;
    }

    .menu-container summary {
      list-style: none; 
      font-size: 26px;
      color: white;
      cursor: pointer;
      user-select: none;
    }

    .menu-container summary::-webkit-details-marker {
      display: none; 
    }

    .menu-box {
      position: absolute;
      top: 40px;
      right: 0;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      padding: 10px;
      min-width: 160px;
    }

    .menu-box a {
      display: block;
      padding: 8px 12px;
      text-decoration: none;
      color: black;
      font-size: 14px;
    }

    .menu-box a:hover {
      background-color: #f0f0f0;
    }

    .menu-box a.logout {
      color: red;
    }
  </style>
</head>
<body>
  <div class="menu-container">
  <details>
    <summary>≡</summary>
    <div class="menu-box">
      <a href="2_pelanggan.php">Dashboard</a>
      <a href="pesanan_pelanggan.php">Pesanan Saya</a>
      <a href="logout.php" class="logout">Logout</a>
    </div>
  </details>
  </div>

  <div class="container">
    <div class="title">Ringkasan Pesanan</div>

    <!-- Daftar Pesanan -->
    <?php foreach ($items as $item): ?>
      <div class="order-item">
        <div>
          <div class="order-name"><?= htmlspecialchars($item['nama_menu']) ?></div>
          <div class="order-qty"><?= htmlspecialchars($item['jumlah']) ?>x</div>
        </div>
        <div class="order-price">
          Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Ringkasan Total -->
    <div class="summary">
      <div><span class="label">Total Dibayar: </span>
        <span class="value">Rp<?= number_format($pesanan['total'], 0, ',', '.') ?></span>
      </div>
    </div>

    <!-- Tombol toggle -->
    <div class="status-toggle">
      <button class="btn-status" onclick="toggleStatus()">Lihat Status Pesanan</button>
    </div>

    <!-- Status -->
    <div class="status-box" id="statusBox">
      <?php
      $statusList = ['Menunggu Konfirmasi', 'Diterima', 'Diproses', 'Selesai'];
      $currentIndex = array_search($pesanan['status'], $statusList);
      foreach ($statusList as $i => $statusText):
        $class = 'step';
        if ($i < $currentIndex) $class .= ' done';
        elseif ($i == $currentIndex) $class .= ' active';
      ?>
        <div class="<?= $class ?>">
          <div class="circle"></div>
          <div class="step-label"><?= $statusText ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script>
    function toggleStatus() {
      const box = document.getElementById('statusBox');
      box.style.display = box.style.display === 'block' ? 'none' : 'block';
    }
  </script>
</body>
</html>
