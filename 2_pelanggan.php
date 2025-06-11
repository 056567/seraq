<?php
session_start();

if (!isset($_SESSION['Nama_pelanggan'])){
    header("Location: 1_pelanggan.php");
    exit();
}

include "service/database_pelanggan.php";
$nama_warung = isset($_SESSION['nama_warung']) ? $_SESSION['nama_warung'] : '';

$cart_items = $_SESSION['cart'][$_SESSION['id_pelanggan']] ?? [];
$ada_pesanan_belum_bayar = !empty($cart_items);

$sql = "SELECT * FROM owner";
// Query dengan rating asli
$sql = "SELECT 
          o.*, 
          AVG(r.nilai) as rata_rating,
          COUNT(r.id_rating) as total_ulasan
        FROM owner o
        LEFT JOIN rating r ON o.id_owner = r.id_owner
        GROUP BY o.id_owner";
$result = $db -> query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan</title>
</head>
<body>
    <?php include "html/2_pelanggan.html" ?> 
</body>
</html>