<?php
session_start();
include 'service/database_owner.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_menu = $_POST['id_menu'] ?? null;
    $nama_menu = $_POST['nama_menu'] ?? null;
    $harga = isset($_POST['harga']) ? (int)$_POST['harga'] : null;
    $deskripsi = $_POST['deskripsi'] ?? null;
    $id_owner = $_SESSION['id_owner'] ?? null;

    if ($id_menu && $nama_menu && $harga !== null && $deskripsi && $id_owner) {
        $stmt = $db->prepare("UPDATE menu SET nama_menu = ?, harga = ?, deskripsi = ? WHERE id_menu = ? AND id_owner = ?");
        $stmt->bind_param("sisii", $nama_menu, $harga, $deskripsi, $id_menu, $id_owner);

        if ($stmt->execute()) {
            echo "OK";
            exit();
        } else {
            echo "ERROR: " . $stmt->error;
            exit();
        }
    } else {
        echo "ERROR: Data tidak lengkap";
        exit();
    }
} else {
    echo "ERROR: Invalid request method";
    exit();
}
?>