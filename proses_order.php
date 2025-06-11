<?php
session_start();

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: 1_pelanggan.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$cart = $_SESSION['cart'][$id_pelanggan] ?? [];
$metode = $_POST['metode_pembayaran'] ?? '';
$total = $_POST['total_harga'] ?? 0;

// Validasi
if (empty($cart) || empty($metode) || $total <= 0) {
    echo "Data pesanan tidak lengkap. <a href='5_pelanggan.php'>Kembali</a>";
    exit();
}

// Buat data riwayat pesanan
$pesanan = [
    'waktu' => date("Y-m-d H:i:s"),
    'metode' => $metode,
    'total' => $total,
    'items' => $cart
];

// Simpan ke session riwayat
$_SESSION['riwayat'][$id_pelanggan][] = $pesanan;

// Kosongkan keranjang
unset($_SESSION['cart'][$id_pelanggan]);

// Redirect ke halaman riwayat
header("Location: 6_riwayat.php");
exit();
