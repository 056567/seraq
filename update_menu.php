<?php
session_start();
include 'service/database_owner.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_owner = $_SESSION['id_owner'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_owner) {
    $action = $_POST['action'] ?? '';

    // CREATE
    if ($action === 'create') {
        $nama_menu = $_POST['nama_menu'] ?? null;
        $harga = isset($_POST['harga']) ? (int)$_POST['harga'] : null;
        $deskripsi = $_POST['deskripsi'] ?? null;
        if ($nama_menu && $harga !== null && $deskripsi) {
            $stmt = $db->prepare("INSERT INTO menu (nama_menu, harga, deskripsi, id_owner, status) VALUES (?, ?, ?, ?, 'Tersedia')");
            $stmt->bind_param("sisi", $nama_menu, $harga, $deskripsi, $id_owner);
            if ($stmt->execute()) {
                echo "OK";
            } else {
                echo "ERROR: " . $stmt->error;
            }
        } else {
            echo "ERROR: Data tidak lengkap";
        }
        exit();
    }

    // DELETE
    if ($action === 'delete') {
        $id_menu = $_POST['id_menu'] ?? null;
        if ($id_menu) {
            $stmt = $db->prepare("DELETE FROM menu WHERE id_menu = ? AND id_owner = ?");
            $stmt->bind_param("ii", $id_menu, $id_owner);
            if ($stmt->execute()) {
                echo "OK";
            } else {
                echo "ERROR: " . $stmt->error;
            }
        } else {
            echo "ERROR: ID menu tidak ditemukan";
        }
        exit();
    }

    // UPDATE (default)
    $id_menu = $_POST['id_menu'] ?? null;
    $nama_menu = $_POST['nama_menu'] ?? null;
    $harga = isset($_POST['harga']) ? (int)$_POST['harga'] : null;
    $deskripsi = $_POST['deskripsi'] ?? null;

    if ($id_menu && $nama_menu && $harga !== null && $deskripsi) {
        $stmt = $db->prepare("UPDATE menu SET nama_menu = ?, harga = ?, deskripsi = ? WHERE id_menu = ? AND id_owner = ?");
        $stmt->bind_param("sisii", $nama_menu, $harga, $deskripsi, $id_menu, $id_owner);
        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "ERROR: " . $stmt->error;
        }
    } else {
        echo "ERROR: Data tidak lengkap";
    }
    exit();
} else {
    echo "ERROR: Invalid request or not logged in";
    exit();
}
?>