<?php
session_start();
include 'service/database_pelanggan.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = json_decode($_POST['items'], true);
    $id_pelanggan = $_POST['id_pelanggan'];
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = "menunggu";

    if (!$db) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    if (!is_array($items) || count($items) == 0) {
        die("Format item tidak valid");
    }

    foreach ($items as $item) {
        $id_menu = $item['id_menu'];
        $jumlah = isset($item['qty']) ? (int)$item['qty'] : 1;
        $catatan = isset($item['catatan']) ? $item['catatan'] : null;
        $harga_item = isset($item['harga']) ? $item['harga'] : 0;
        $total_item = $harga_item * $jumlah;

        // Pastikan kolom sesuai dengan tabel pesanan kamu!
        $stmt = $db->prepare("INSERT INTO pesanan 
            (id_pelanggan, id_menu, jumlah, catatan, status, total_harga, metode_pembayaran, waktu_pesan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiissds", $id_pelanggan, $id_menu, $jumlah, $catatan, $status, $total_item, $metode_pembayaran);
        $stmt->execute();
    }

    // Kosongkan keranjang setelah checkout
    unset($_SESSION['cart'][$id_pelanggan]);

    echo "<script>alert('Pesanan berhasil disimpan!'); window.location.href='2_pelanggan.php';</script>";
} else {
    echo "Akses tidak valid.";
}
?>