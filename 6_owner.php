<?php
session_start();
include 'service/database_owner.php';

$id_owner = $_SESSION['id_owner'] ?? null;

// Inisialisasi struktur data untuk chart
$chartData = [
    'harian' => ['labels' => [], 'data' => [], 'detail' => [], 'total' => 0],
    'mingguan' => ['labels' => [], 'data' => [], 'detail' => [], 'total' => 0],
    'bulanan' => ['labels' => [], 'data' => [], 'detail' => [], 'total' => 0],
    'tahunan' => ['labels' => [], 'data' => [], 'detail' => [], 'total' => 0]
];

if ($id_owner) {
    // Query untuk mengambil data pesanan yang selesai
    $query = "SELECT 
                p.id_pesanan, 
                p.waktu_pesan,
                p.total_harga,
                m.nama_menu
              FROM pesanan p
              JOIN menu m ON p.id_menu = m.id_menu
              WHERE m.id_owner = '$id_owner' AND p.status = 'selesai'
              ORDER BY p.waktu_pesan DESC";
    $result = mysqli_query($db, $query);

    // Tanggal saat ini untuk filter
    $today = date('Y-m-d');
    $current_week = date('Y-W');
    $current_month = date('Y-m');
    $current_year = date('Y');

    while ($row = mysqli_fetch_assoc($result)) {
        $tanggal = date('Y-m-d', strtotime($row['waktu_pesan']));
        $minggu = date('Y-W', strtotime($row['waktu_pesan']));
        $bulan = date('Y-m', strtotime($row['waktu_pesan']));
        $tahun = date('Y', strtotime($row['waktu_pesan']));
        $nama_menu = $row['nama_menu'];
        $harga = $row['total_harga'];

        // HARIAN (data hari ini saja)
        if ($tanggal == $today) {
            if (!isset($chartData['harian']['detail'][$nama_menu])) {
                $chartData['harian']['detail'][$nama_menu] = 0;
            }
            $chartData['harian']['detail'][$nama_menu] += $harga;
            $chartData['harian']['total'] += $harga;
        }

        // MINGGUAN (data minggu ini saja)
        if ($minggu == $current_week) {
            // Format tanggal menjadi 'd M' (contoh: 12 Jun)
            $label_tanggal = date('d M', strtotime($tanggal));
            if (!isset($chartData['mingguan']['detail'][$label_tanggal])) {
                $chartData['mingguan']['detail'][$label_tanggal] = 0;
            }
            $chartData['mingguan']['detail'][$label_tanggal] += $harga;
            $chartData['mingguan']['total'] += $harga;
        }

        // BULANAN (data bulan ini saja)
        if ($bulan == $current_month) {
            // Hitung minggu ke berapa dalam bulan (1-5)
            $minggu_ke = ceil(date('j', strtotime($tanggal)) / 7);
            $label_minggu = "Minggu " . (int)$minggu_ke;
            if (!isset($chartData['bulanan']['detail'][$label_minggu])) {
                $chartData['bulanan']['detail'][$label_minggu] = 0;
            }
            $chartData['bulanan']['detail'][$label_minggu] += $harga;
            $chartData['bulanan']['total'] += $harga;
        }

        // TAHUNAN (data tahun ini saja)
        if ($tahun == $current_year) {
            $nama_bulan = date('M', strtotime($tanggal));
            if (!isset($chartData['tahunan']['detail'][$nama_bulan])) {
                $chartData['tahunan']['detail'][$nama_bulan] = 0;
            }
            $chartData['tahunan']['detail'][$nama_bulan] += $harga;
            $chartData['tahunan']['total'] += $harga;
        }
    }

    // Format data untuk chart: labels dan data array
    foreach (['harian', 'mingguan', 'bulanan', 'tahunan'] as $type) {
        $chartData[$type]['labels'] = array_keys($chartData[$type]['detail']);
        $chartData[$type]['data'] = array_values($chartData[$type]['detail']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
</head>
<body>
    <?php include "html/6_owner.html" ?> 
    <script>
    const chartDataFromPHP = <?= json_encode($chartData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?>;
    </script>
</body>
</html>