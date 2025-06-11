<?php 
session_start();

// kalo belum login, redirect
if (!isset($_SESSION['nama_warung'])){
    header("Location: login_owner.php");
    exit();
}

$nama_warung = $_SESSION['nama_warung'];
$page = $_GET['page'] ?? 'dashboard';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
</head>
<body>
    <?php 
    switch ($page) {
        case 'profile_warung':
            include "profile_warung.php";
            break;
        default:
            include "html/2_owner.html"; // ubah sesuai isi dashboard
            break;
    }
    ?>
</body>
</html>
