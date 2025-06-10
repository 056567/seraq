<?php
session_start();
include 'service/database_pelanggan.php'; // Atau database_config.php jika ada satu file untuk semua koneksi

// Pastikan pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: 1_pelanggan.php");
    exit();
}

// Pastikan ini adalah POST request dan tombol konfirmasi ditekan
if (isset($_POST['konfirmasi_pesanan'])) {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $total_harga = $_POST['total_harga'] ?? 0;
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? 'Tunai'; // Default jika tidak dipilih

    // Ambil item dari keranjang session
    $cart_items = $_SESSION['cart'] ?? [];

    if (empty($cart_items)) {
        echo "Keranjang kosong. Tidak ada yang bisa dipesan.";
        // Anda bisa redirect kembali ke 5_pelanggan.php atau 2_pelanggan.php
        header("Location: 5_pelanggan.php?message=empty_cart");
        exit();
    }

    // Mulai transaksi database
    mysqli_begin_transaction($db);

    try {
        // 1. Masukkan data ke tabel 'pesanan'
        // Tentukan id_owner dari salah satu item di keranjang (asumsi satu pesanan untuk satu owner)
        // Jika pelanggan bisa memesan dari banyak owner sekaligus dalam satu keranjang,
        // maka Anda perlu membuat pesanan terpisah untuk setiap owner.
        // Untuk saat ini, kita asumsikan satu keranjang hanya untuk satu owner.
        // Jika ada banyak owner, Anda perlu mengelompokkan cart_items berdasarkan id_owner dan membuat pesanan terpisah.
        // Untuk contoh sederhana ini, kita ambil id_owner dari item pertama di keranjang.
        $id_owner_pesanan = $cart_items[0]['id_owner'] ?? null; // Ambil id_owner dari item pertama

        if (is_null($id_owner_pesanan)) {
            throw new Exception("ID Owner tidak ditemukan di keranjang.");
        }

        $status_pesanan = 'Menunggu Konfirmasi'; // Status awal pesanan
        $query_pesanan = "INSERT INTO pesanan (id_pelanggan, id_owner, waktu_pesan, status, total_harga) VALUES (?, ?, NOW(), ?, ?)";
        $stmt_pesanan = $db->prepare($query_pesanan);
        $stmt_pesanan->bind_param("iiss", $id_pelanggan, $id_owner_pesanan, $status_pesanan, $total_harga); // Menambahkan id_owner_pesanan
        
        if (!$stmt_pesanan->execute()) {
            throw new Exception("Gagal menyimpan pesanan: " . $stmt_pesanan->error);
        }
        $id_pesanan_baru = mysqli_insert_id($db);

        // 2. Masukkan data ke tabel 'detail_pesanan'
        $query_detail = "INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES (?, ?, ?, ?)";
        $stmt_detail = $db->prepare($query_detail);

        foreach ($cart_items as $item) {
            $id_menu = $item['id_menu'];
            $jumlah = $item['qty'];
            $subtotal_item = $item['harga'] * $item['qty'];
            
            $stmt_detail->bind_param("iiid", $id_pesanan_baru, $id_menu, $jumlah, $subtotal_item);
            if (!$stmt_detail->execute()) {
                throw new Exception("Gagal menyimpan detail pesanan untuk menu ID " . $id_menu . ": " . $stmt_detail->error);
            }
        }

        // 3. Masukkan data ke tabel 'transaksi'
        $query_transaksi = "INSERT INTO transaksi (id_pesanan, metode_pembayaran, waktu_bayar, total_bayar) VALUES (?, ?, NOW(), ?)";
        $stmt_transaksi = $db->prepare($query_transaksi);
        $stmt_transaksi->bind_param("isd", $id_pesanan_baru, $metode_pembayaran, $total_harga);
        if (!$stmt_transaksi->execute()) {
            throw new Exception("Gagal menyimpan transaksi: " . $stmt_transaksi->error);
        }

        // Komit transaksi jika semua berhasil
        mysqli_commit($db);

        // Hapus keranjang setelah pesanan berhasil
        unset($_SESSION['cart']);

        // Redirect ke halaman sukses atau halaman pesanan saya
        header("Location: order_success.php?order_id=" . $id_pesanan_baru);
        exit();

    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        mysqli_rollback($db);
        die("Terjadi kesalahan saat memproses pesanan: " . $e->getMessage());
    }
} else {
    // Jika diakses langsung tanpa submit form
    header("Location: 5_pelanggan.php");
    exit();
}
?>