<?php
session_start(); // Pastikan session dimulai di awal file

// Pastikan pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: 1_pelanggan.php");
    exit();
}

include 'service/database_pelanggan.php'; // koneksi DB

if (isset($_POST['konfirmasi_pesanan'])) {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $cart_items = $_SESSION['cart'][$id_pelanggan] ?? [];
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';

    if (empty($cart_items)) {
        die("Keranjang kosong, tidak bisa memproses pesanan.");
    }

    if (empty($metode_pembayaran)) {
        die("Silakan pilih metode pembayaran.");
    }

    $query = "INSERT INTO pesanan 
              (id_pelanggan, id_menu, id_owner, jumlah, catatan, waktu_pesan, status, total_harga, metode_pembayaran)
              VALUES (?, ?, ?, ?, ?, NOW(), 'Menunggu', ?, ?)";
    $stmt = $db->prepare($query);

    foreach ($cart_items as $item) {
        $id_menu = $item['id_menu'];
        $id_owner = $item['id_owner'];
        $jumlah = $item['qty'];
        $catatan = $item['catatan'] ?? '';
        $total = $item['harga'] * $jumlah;

        $stmt->bind_param("iiiisiss", $id_pelanggan, $id_menu, $id_owner, $jumlah, $catatan, $total, $metode_pembayaran);
        $stmt->execute();
    }

    unset($_SESSION['cart'][$id_pelanggan]);
    header("Location: sukses.php?status=berhasil");
    exit();
}


// Ambil data keranjang dari session
$id_pelanggan = $_SESSION['id_pelanggan'];
$cart_items = $_SESSION['cart'][$id_pelanggan] ?? []; // Gunakan operator null coalescing untuk menghindari error jika session belum ada
$total_harga = 0;

foreach ($cart_items as $item) {
    // Pastikan kunci 'harga' dan 'qty' ada sebelum digunakan
    $item_harga = isset($item['harga']) ? $item['harga'] : 0;
    $item_qty = isset($item['qty']) ? $item['qty'] : 0;
    $total_harga += $item_harga * $item_qty;
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
    <?php include "html/5_pelanggan.html" ?> 
</body>
</html>