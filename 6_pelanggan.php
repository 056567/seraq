<?php
session_start();
include 'service/database_pelanggan.php';

if (!isset($conn)) {
    $conn = new mysqli("localhost", "root", "", "seraq"); // sesuaikan nama database
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
}

// Pastikan user login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit;
}

$id_pesanan = $_GET['id_pesanan'] ?? null;
$id_pelanggan = $_SESSION['id_pelanggan'];

if (!$id_pesanan) {
    echo "ID pesanan tidak valid.";
    exit;
}

// Ambil info utama pesanan (total, status)
$query = "SELECT total_harga AS total, status FROM pesanan WHERE id_pesanan = ? AND id_pelanggan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_pesanan, $id_pelanggan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

$pesanan = $result->fetch_assoc();
$stmt->close();

// Ambil detail menu yang dipesan (JOIN ke tabel menu)
$query = "
    SELECT m.nama_menu, p.jumlah, m.harga
    FROM pesanan p
    JOIN menu m ON p.id_menu = m.id_menu
    WHERE p.id_pesanan = ? AND p.id_pelanggan = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_pesanan, $id_pelanggan);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
$stmt->close();
$conn->close();

// Tampilkan dengan template
include __DIR__ . '/html/6_pelanggan_template.php';
?>
