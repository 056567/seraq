<?php 
session_start();

if (isset($_GET['warung'])){
    $_SESSION['nama_warung'] = $_GET['warung'];
}
$nama_warung = isset($_SESSION['nama_warung']) ? $_SESSION['nama_warung'] : 'Warung';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "html/pelanggan3.html" ?> 
</body>
</html>