<?php
include 'service/database_pelanggan.php'; // sesuaikan path kalau perlu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = json_decode($_POST['items'], true);
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_owner = $_POST['id_owner'];
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = "menunggu";

    if (!$db) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    foreach ($items as $item) {
        $id_menu = $item['id_menu'];
        $jumlah = isset($item['qty']) ? $item['qty'] : 1;
        $catatan = isset($item['catatan']) ? $item['catatan'] : NULL;

        $stmt = $db->prepare("INSERT INTO pesanan (id_pelanggan, id_menu, id_owner, jumlah, catatan, status, total_harga) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiissd", $id_pelanggan, $id_menu, $id_owner, $jumlah, $catatan, $status, $total_harga);
        $stmt->execute();
    }

    echo "<script>alert('Pesanan berhasil disimpan!'); window.location.href='2_pelanggan.php';</script>";
} else {
    echo "Akses tidak valid.";
}
?>
