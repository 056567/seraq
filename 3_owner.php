<?php 
include 'service/database_owner.php';
session_start();

$id_owner = $_SESSION['id_owner'] ?? null;

if ($id_owner) {
    $sql = "SELECT * FROM menu WHERE id_owner = $id_owner";
    $result = mysqli_query($db, $sql);
} else {
    $result = false; // tidak ada data ditampilkan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "html/3_owner.html" ?> 
</body>
</html>