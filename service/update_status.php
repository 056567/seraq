<?php
include 'database_owner.php';

$id_pesanan = $_POST['id_pesanan'] ?? '';
$status = $_POST['status'] ?? '';

if ($id_pesanan && in_array($status, ['proses', 'ditolak', 'selesai'])) {
    $stmt = $db->prepare("UPDATE pesanan SET status=? WHERE id_pesanan=?");
    $stmt->bind_param("ss", $status, $id_pesanan); // <-- ubah jadi "ss"
    $stmt->execute();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}