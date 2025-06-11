<?php
session_start();
include 'service/database_owner.php';

$id_owner = $_SESSION['id_owner'] ?? null;
$laporan = [
    'harian' => [],
    'mingguan' => [],
    'bulanan' => [],
    'tahunan' => []
];

if ($id_owner) {
    // Ambil semua pesanan selesai milik owner
    $query = "SELECT 
                p.id_pesanan, 
                p.waktu_pesan,
                p.total_harga,
                m.nama_menu,
                p.jumlah
              FROM pesanan p
              JOIN menu m ON p.id_menu = m.id_menu
              WHERE m.id_owner = '$id_owner' AND p.status = 'selesai'
              ORDER BY p.waktu_pesan DESC";
    $result = mysqli_query($db, $query);

    // Proses data ke dalam array laporan
    while ($row = mysqli_fetch_assoc($result)) {
        $tanggal = date('Y-m-d', strtotime($row['waktu_pesan']));
        $minggu = date('o-W', strtotime($row['waktu_pesan']));
        $bulan = date('Y-m', strtotime($row['waktu_pesan']));
        $tahun = date('Y', strtotime($row['waktu_pesan']));

        // Harian (per menu)
        $key = $row['nama_menu'];
        if (!isset($laporan['harian'][$tanggal][$key])) $laporan['harian'][$tanggal][$key] = 0;
        $laporan['harian'][$tanggal][$key] += $row['total_harga'];

        // Mingguan (total per hari)
        if (!isset($laporan['mingguan'][$minggu][$tanggal])) $laporan['mingguan'][$minggu][$tanggal] = 0;
        $laporan['mingguan'][$minggu][$tanggal] += $row['total_harga'];

        // Bulanan (total per minggu)
        $minggu_ke = ceil(date('j', strtotime($row['waktu_pesan'])) / 7);
        $minggu_label = "Minggu $minggu_ke";
        if (!isset($laporan['bulanan'][$bulan][$minggu_label])) $laporan['bulanan'][$bulan][$minggu_label] = 0;
        $laporan['bulanan'][$bulan][$minggu_label] += $row['total_harga'];

        // Tahunan (total per bulan)
        $bulan_label = date('M', strtotime($row['waktu_pesan']));
        if (!isset($laporan['tahunan'][$tahun][$bulan_label])) $laporan['tahunan'][$tahun][$bulan_label] = 0;
        $laporan['tahunan'][$tahun][$bulan_label] += $row['total_harga'];
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
    <?php include "html/6_owner.html" ?> 
</body>
</html>