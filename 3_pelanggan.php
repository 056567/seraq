<?php
include 'service/database_pelanggan.php';

$id_owner = isset($_GET['id_owner']) ? intval($_GET['id_owner']) : 0;

if ($id_owner > 0) {
    $query = "SELECT * FROM owner WHERE id_owner = ?";
    $sql = $db -> prepare($query);
    $sql -> bind_param("i", $id_owner);
    $sql -> execute();
    $result = $sql -> get_result();
    $data = $result -> fetch_assoc();

    // Ambil data rating rata-rata dari tabel rating
    $query_rating = "SELECT AVG(nilai) as rata_rating FROM rating WHERE id_owner = ?";
    $sql_rating = $db->prepare($query_rating);
    $sql_rating->bind_param("i", $id_owner);
    $sql_rating->execute();
    $result_rating = $sql_rating->get_result();
    $data_rating = $result_rating->fetch_assoc();
    $rating = round($data_rating['rata_rating'], 1); // dibulatkan 1 angka di belakang koma

     // Ambil menu
    $query_menu = "SELECT * FROM menu WHERE id_owner = ?";
    $sql_menu = $db->prepare($query_menu);
    $sql_menu->bind_param("i", $id_owner);
    $sql_menu->execute();
    $result_menu = $sql_menu->get_result();
    $menu = []; // Inisialisasi array menu
    while ($row = $result_menu->fetch_assoc()) {
        $menu[] = $row;
    }


    if ($data) {
        // echo "<h2>" . htmlspecialchars($data['nama_warung']) . "</h2>";
        // echo "<p>" . htmlspecialchars($data['deskripsi_warung']) . "</p>";
    } else {
        echo "Warung tidak ditemukan.";
    }
} else {
    echo "ID owner tidak valid.";
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
    <?php include "html/3_pelanggan.html" ?> 
</body>
</html>