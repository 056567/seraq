<?php
session_start();
// config database
include "service/database_pelanggan.php";

// Jika tombol LOGIN ditekan
if (isset($_POST['PESAN'])) {
    // Validasi : pastiin no_meja numerik dan > 0
    if (!is_numeric($_POST['No_meja']) || !isset($_POST['No_meja'])){
        die("Nomor meja tidak ada.");
    }

    $Nama = mysqli_real_escape_string($db, $_POST['Nama']);
    $No_meja = (int)$_POST['No_meja'];

    $query = "INSERT INTO pelanggan (Nama, No_meja) VALUES ('$Nama', '$No_meja')";
    // mysqli_query($db, $query);
    // $result = mysqli_query($db, $query);

     if (mysqli_query($db, $query)) {
        // Ambil ID pelanggan yang baru saja di-insert
        $id_pelanggan_baru = mysqli_insert_id($db);

        // Simpan data pelanggan ke session
        $_SESSION['id_pelanggan'] = $id_pelanggan_baru; // <-- INI YANG PENTING
        $_SESSION['nama_pelanggan'] = $Nama;
        $_SESSION['no_meja'] = $No_meja;

        // redirect ke dashboard
        header("Location: 2_pelanggan.php");
        exit();
    } else {
        die("Gagal memesan : " . mysqli_error($db));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan</title>
</head>
<body>
    <?php include "html/1_pelanggan.html"; ?>
</body>
</html>
