<?php
session_start(); // Pastikan session dimulai di awal file

include 'service/database_pelanggan.php';

// Pastikan pelanggan sudah login dan id_pelanggan tersedia di session
if (!isset($_SESSION['id_pelanggan'])) {
    // Redirect ke halaman login jika id_pelanggan tidak ada
    header("Location: 1_pelanggan.php");
    exit();
}

if (!isset($_SESSION['nama_pelanggan']) || !isset($_SESSION['no_meja'])) {
    // Session belum lengkap, mungkin user belum login
    header("Location: 1_pelanggan.php");
    exit();
}

// Ambil data dari session
$nama_pelanggan = $_SESSION['nama_pelanggan'];
$no_meja = $_SESSION['no_meja'];
$id_owner = $_SESSION['id_owner']; // ID owner dari session

$id_menu = isset($_GET['id_menu']) ? intval($_GET['id_menu']) : 0;

if ($id_menu > 0) {
    $query = "SELECT * FROM menu WHERE id_menu = ?";
    $sql = $db->prepare($query);
    $sql->bind_param("i", $id_menu);
    $sql->execute();
    $result = $sql->get_result();
    $menu_data = $result->fetch_assoc(); // Simpan data menu ke $menu_data

    if (!$menu_data) { // Jika menu tidak ditemukan
        die("Menu tidak ditemukan.");
    }
} else {
    die("ID menu tidak valid.");
}

// --- LOGIKA PENAMBAHAN KE KERANJANG ---
if (isset($_POST['tambah_ke_keranjang'])) { // Tombol submit memiliki name="tambah_ke_keranjang"
    $id_menu_post = isset($_POST['id_menu']) ? intval($_POST['id_menu']) : 0;
    $qty_post = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
    $catatan_post = isset($_POST['catatan']) ? mysqli_real_escape_string($db, $_POST['catatan']) : '';
    $id_owner_post = isset($_POST['id_owner']) ? intval($_POST['id_owner']) : 0;

    // Amankan dan validasi input
    if ($id_menu_post <= 0 || $qty_post < 1) {
        die("Input tidak valid untuk menambahkan ke keranjang.");
    }

    // Ambil detail menu lagi dari database (untuk memastikan data valid)
    $query_item = "SELECT * FROM menu WHERE id_menu = ?";
    $sql_item = $db->prepare($query_item);
    $sql_item->bind_param("i", $id_menu_post);
    $sql_item->execute();
    $result_item = $sql_item->get_result();
    $item_detail = $result_item->fetch_assoc();

    $id_pelanggan = $_SESSION['id_pelanggan'];
    if (!isset($_SESSION['cart'][$id_pelanggan])) {
        $_SESSION['cart'][$id_pelanggan] = [];
    } else {
        // Cek owner di keranjang
        $cart = $_SESSION['cart'][$id_pelanggan];
        if (!empty($cart)) {
            $cart_owner = $cart[0]['id_owner'];
            if ($cart_owner != $id_owner_post) {
                // Jika owner berbeda, bisa tampilkan pesan error atau reset keranjang
                // Contoh: reset keranjang
                $_SESSION['cart'][$id_pelanggan] = [];
                // Atau: die("Keranjang hanya boleh berisi menu dari satu warung!");
            }
        }
    }

    if ($item_detail) {
        $new_item = [
            'id_menu' => $item_detail['id_menu'],
            'nama_menu' => $item_detail['nama_menu'],
            'harga' => $item_detail['harga'],
            'qty' => $qty_post,
            'catatan' => $catatan_post,
            'id_owner' => $id_owner_post // Simpan id_owner ke item keranjang
        ];

        $id_pelanggan = $_SESSION['id_pelanggan'];
        // Inisialisasi keranjang jika belum ada
        if (!isset($_SESSION['cart'][$id_pelanggan])) {
            $_SESSION['cart'][$id_pelanggan] = [];
        }

        // Cek apakah item sudah ada di keranjang (berdasarkan id_menu DAN catatan)
        $item_found = false;
        foreach ($_SESSION['cart'][$id_pelanggan] as &$cart_item) { // Gunakan referensi (&) untuk memodifikasi langsung
            if ($cart_item['id_menu'] == $new_item['id_menu'] && $cart_item['catatan'] == $new_item['catatan']) {
                $cart_item['qty'] += $new_item['qty']; // Tambahkan kuantitas
                $item_found = true;
                break;
            }
        }

        if (!$item_found) {
            $_SESSION['cart'][$id_pelanggan][] = $new_item; // Tambahkan item baru
        }

        // Redirect ke halaman keranjang setelah menambahkan item
        header("Location: 3_pelanggan.php?id_owner=" . $id_owner_post);
        exit(); // Penting: hentikan eksekusi script setelah redirect
    } else {
        die("Detail menu tidak ditemukan saat menambahkan ke keranjang.");
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($menu_data['nama_menu'] ?? 'Detail Menu') ?></title>
</head>
<body>
    <?php
    // Pastikan variabel $menu tersedia di html/4_pelanggan.html
    // Kita sudah punya $menu_data dari query di atas, jadi kita bisa pakai itu.
    $menu = $menu_data; // Assign $menu_data ke $menu agar sesuai dengan $menu[] di HTML
    include "html/4_pelanggan.html";
    ?> 
</body>
</html>
