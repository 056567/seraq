<?php
//session_start();
include "service/database_owner.php";

if (!isset($_SESSION['id_owner'])) {
    header("Location: 1_owner.php");
    exit();
}

$id_owner = $_SESSION['id_owner'];
$success = "";
$error = "";

// Kalau form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = mysqli_real_escape_string($db, $_POST["nama"]);
    $no_hp = mysqli_real_escape_string($db, $_POST["no_hp"]);
    $email = mysqli_real_escape_string($db, $_POST["email"]);
    $deskripsi = mysqli_real_escape_string($db, $_POST["deskripsi"]);

    $update = "UPDATE owner SET 
                nama_warung = '$nama',
                no_hp = '$no_hp',
                email = '$email',
                deskripsi = '$deskripsi'
               WHERE id_owner = '$id_owner'";

    if (mysqli_query($db, $update)) {
        $success = "Profil berhasil diperbarui!";
        $_SESSION['nama_warung'] = $nama;
    } else {
        $error = "Gagal memperbarui profil: " . mysqli_error($db);
    }
}

// Ambil data
$query = "SELECT * FROM owner WHERE id_owner = '$id_owner'";
$result = mysqli_query($db, $query);
$data = mysqli_fetch_assoc($result);

include "html/profile_warung.html";
?>