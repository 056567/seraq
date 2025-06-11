<?php
session_start();
// config database
include "service/database_owner.php";

$error = "";

// Jika tombol LOGIN ditekan
if (isset($_POST['LOGIN'])) {
    $username = mysqli_real_escape_string($db, $_POST["username"]);
    $password = mysqli_real_escape_string($db, $_POST["password"]);
 
    $query = "SELECT * FROM owner WHERE username='$username' AND password='$password'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id_owner'] = $row['id_owner'];
        $_SESSION['nama_warung'] = $row['nama_warung'];
        header("Location: 2_owner.php");
        exit(); // Penting supaya script berhenti
    } else {
        $error = "Username atau Password Salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Owner</title>
</head>
<body>
    <?php include "html/1_owner.html"; ?>
</body>
</html>
