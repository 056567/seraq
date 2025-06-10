<?php
session_start(); // Pastikan session dimulai di awal file

// Pastikan pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: 1_pelanggan.php");
    exit();
}

// TIDAK PERLU ADA LOGIKA POST DI SINI LAGI.
// Item sudah ditambahkan ke session di 4_pelanggan.php, lalu di-redirect ke sini.

// Ambil data keranjang dari session
$cart_items = $_SESSION['cart'] ?? []; // Gunakan operator null coalescing untuk menghindari error jika session belum ada
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