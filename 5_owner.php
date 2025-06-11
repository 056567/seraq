<?php 
session_start();
include 'service/database_owner.php';

// Validasi session
if (!isset($_SESSION['id_owner'])) {
    header("Location: 1_owner.php"); // redirect ke login jika belum login
    exit;
}

$nama_warung = $_SESSION['nama_warung'] ?? 'Owner';
$id_owner = $_SESSION['id_owner'];

// Query pesanan + menu + pelanggan
$query = "SELECT 
            p.id_pesanan, 
            p.status AS status_pesanan,
            p.waktu_pesan AS waktu_pesanan,
            p.catatan AS keterangan,
            p.jumlah,
            p.total_harga,
            m.nama_menu,
            m.harga,
            pel.nama AS nama_pelanggan,
            pel.no_meja
          FROM pesanan p
          JOIN menu m ON p.id_menu = m.id_menu
          JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
          WHERE m.id_owner = '$id_owner'
          ORDER BY p.waktu_pesan DESC";

$result = mysqli_query($db, $query);

$pesanan_data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id_pesanan'];

    if (!isset($pesanan_data[$id])) {
        $pesanan_data[$id] = [
            'waktu' => date('d M Y, H:i', strtotime($row['waktu_pesanan'])),
            'menu' => '',
            'total' => 0,
            'keterangan' => $row['keterangan'],
            'status' => $row['status_pesanan'],
            'nama_pelanggan' => $row['nama_pelanggan'],
            'no_meja' => $row['no_meja']
        ];
    }

    $baris_menu = $row['nama_menu'] . ' x' . $row['jumlah'] . ' - Rp' . number_format($row['harga'] * $row['jumlah'], 0, ',', '.');
    $pesanan_data[$id]['menu'] .= $baris_menu . '<br>';
    $pesanan_data[$id]['total'] += $row['harga'] * $row['jumlah'];
}

// Format total harga setelah pengelompokan
foreach ($pesanan_data as &$pesanan) {
    $pesanan['total'] = 'Rp' . number_format($pesanan['total'], 0, ',', '.');
}
unset($pesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pesanan Masuk - SeraQ</title>
</head>
<body>
  <?php include "html/5_owner.html"; ?> 

  <script>
    // Data pesanan diisi dari PHP
    const pesananData = <?= json_encode($pesanan_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?>;
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof renderPesanan === 'function') {
            renderPesanan();
        } else {
            console.warn("Fungsi renderPesanan belum terdefinisi saat data dimuat.");
        }
    });

    function statusText(status) {
      switch (status) {
        case 'menunggu': return 'Menunggu Konfirmasi';
        case 'proses': return 'Sedang Diproses';
        case 'selesai': return 'Selesai';
        case 'ditolak': return 'Ditolak';
        default: return status;
      }
    }
  </script>
</body>
</html>