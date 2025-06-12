<?php
include "service/database_owner.php";
//session_start();

if (!isset($_SESSION['id_owner'])) {
    header("Location: 1_owner.php");
    exit();
}

$id_owner = $_SESSION['id_owner'];
$success = "";
$error = "";

// Ambil data dari DB dulu
$query = "SELECT * FROM owner WHERE id_owner = '$id_owner'";
$result = mysqli_query($db, $query);
$data = mysqli_fetch_assoc($result);

// Default gambar lama
$gambar = $data['gambar'] ?? ""; // kalau null, tetap aman

// Kalau form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = mysqli_real_escape_string($db, $_POST["nama"]);
    $no_hp = mysqli_real_escape_string($db, $_POST["no_hp"]);
    $email = mysqli_real_escape_string($db, $_POST["email"]);
    $deskripsi = mysqli_real_escape_string($db, $_POST["deskripsi"]);

    // Cek jika upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $targetDir = "uploads/";
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid("warung_", true) . "." . $ext;
        $targetPath = $targetDir . $newFilename;

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $allowedExt)) {
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
                $gambar = $newFilename;
            } else {
                $error = "Gagal mengunggah gambar.";
            }
        } else {
            $error = "Format gambar tidak diperbolehkan.";
        }
    }

    // Update DB
    $update = "UPDATE owner SET 
                nama_warung = '$nama',
                no_hp = '$no_hp',
                email = '$email',
                deskripsi = '$deskripsi',
                gambar = '$gambar'
               WHERE id_owner = '$id_owner'";

    if (mysqli_query($db, $update)) {
        $success = "Profil berhasil diperbarui!";
        $_SESSION['nama_warung'] = $nama;
    } else {
        $error = "Gagal memperbarui profil: " . mysqli_error($db);
    }
}

include "html/profile_warung.html";
?>
