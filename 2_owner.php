<?php 
session_start();

// kalo belom login, redirect
//if (!isset($_SESSION['nama_warung'])){
    // header("Location: login_owner.php");
  //  exit();
//}

$nama_warung = $_SESSION['nama_warung'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "html/2_owner.html" ?> 
</body>
</html>