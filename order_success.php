<?php
session_start();

// Pastikan pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: 1_pelanggan.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 'N/A';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #28a745;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            font-size: 1.1em;
            margin-bottom: 15px;
        }
        .order-id {
            font-weight: bold;
            color: #007bff;
        }
        .button-group {
            margin-top: 30px;
        }
        .button-group a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button-group a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pesanan Berhasil Dibuat!</h1>
        <p>Terima kasih atas pesanan Anda. Pesanan Anda dengan ID <span class="order-id">#<?= htmlspecialchars($order_id) ?></span> telah berhasil diproses.</p>
        <p>Mohon tunggu konfirmasi dari pihak warung.</p>
        <div class="button-group">
            <a href="2_pelanggan.php">Kembali ke Dashboard</a>
            <a href="logout_pelanggan.php">Logout</a>
        </div>
    </div>
</body>
</html>