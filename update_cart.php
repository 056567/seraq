<?php
session_start();

if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: 1_pelanggan.php");
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$id_menu = $_POST['id_menu'];
$action = $_POST['action'];

if (isset($_SESSION['cart'][$id_pelanggan][$id_menu])) {
    if ($action === 'plus') {
        $_SESSION['cart'][$id_pelanggan][$id_menu]['qty'] += 1;
    } elseif ($action === 'minus') {
        $_SESSION['cart'][$id_pelanggan][$id_menu]['qty'] -= 1;

        if ($_SESSION['cart'][$id_pelanggan][$id_menu]['qty'] <= 0) {
            unset($_SESSION['cart'][$id_pelanggan][$id_menu]);
        }
    }
}

header("Location: 5_pelanggan.php");
exit;
