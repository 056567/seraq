<?php
session_start();
include 'service/database_ulasan.php'; 

$id_owner = $_GET['id_owner'] ?? 0;

// Ambil data warung
$stmt = $db->prepare("SELECT * FROM owner WHERE id_owner = ?");
$stmt->bind_param("i", $id_owner);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Ambil ulasan (pastikan kolom sesuai dengan database: nilai, komentar, waktu_rating)
$stmt = $db->prepare("SELECT r.nilai, r.komentar, r.waktu_rating, p.nama AS nama_pelanggan 
    FROM rating r 
    JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan 
    WHERE r.id_owner = ? 
    ORDER BY r.waktu_rating DESC");
$stmt->bind_param("i", $id_owner);
$stmt->execute();
$ulasan_list = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include 'html/ulasan.html';
?>