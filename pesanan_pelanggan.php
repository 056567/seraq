<?php
include 'service/database_pelanggan.php';
session_start();

$id_pelanggan = $_SESSION['id_pelanggan'] ?? 0;

$query = "SELECT * FROM pesanan WHERE id_pelanggan = ? ORDER BY waktu_pesan DESC";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$result = $stmt->get_result();

$pesanan = [];
while ($row = $result->fetch_assoc()) {
    $pesanan[] = $row;
}

include __DIR__ . '/html/pesanan_pelanggan.html';
?>