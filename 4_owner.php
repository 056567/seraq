<?php 
session_start();
include 'service/database_owner.php';

$nama_warung = $_SESSION['nama_warung'] ?? 'Owner';

$id_owner = $_SESSION['id_owner'] ?? null;
$transaksi_data = [];

if ($id_owner) {
    $query = "SELECT 
                p.id_pesanan, 
                p.waktu_pesan,
                p.total_harga,
                pel.nama AS nama_pelanggan,
                pel.no_meja
              FROM pesanan p
              JOIN menu m ON p.id_menu = m.id_menu
              JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
              WHERE m.id_owner = '$id_owner' AND p.status = 'selesai'
              ORDER BY p.waktu_pesan DESC";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id_pesanan'];
        $transaksi_data[$id] = [
            'waktu' => date('d M Y, H:i', strtotime($row['waktu_pesan'])),
            'total' => 'Rp' . number_format($row['total_harga'], 0, ',', '.'),
            'nama_pelanggan' => $row['nama_pelanggan'],
            'no_meja' => $row['no_meja']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "html/4_owner.html" ?> 
    <script>
    const transaksiData = <?= json_encode($transaksi_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?>;

    // Contoh render otomatis ke tabel dengan id="tabel-transaksi"
    document.addEventListener("DOMContentLoaded", () => {
        const tabel = document.getElementById('tabel-transaksi');
        if (!tabel) return;
        let html = '';
        let no = 1;
        for (const [id, data] of Object.entries(transaksiData)) {
            html += `<tr>
                <td>${no++}</td>
                <td>${data.nama_pelanggan}</td>
                <td>${data.no_meja}</td>
                <td>${data.waktu}</td>
                <td>${data.total}</td>
            </tr>`;
        }
        tabel.innerHTML = html || '<tr><td colspan="5">Belum ada transaksi selesai</td></tr>';
    });
    </script>
</body>
</html>