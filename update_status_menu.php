<?php
session_start();
include 'service/database_owner.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug received data
    echo "<pre>Received POST data:\n";
    print_r($_POST);
    echo "</pre>";
    
    $id_menu = $_POST['id_menu'] ?? null;
    $status = $_POST['status'] ?? null;
    $id_owner = $_SESSION['id_owner'] ?? null;

    if ($id_menu && $status && $id_owner) {
        $stmt = $db->prepare("UPDATE menu SET status = ? WHERE id_menu = ? AND id_owner = ?");
        $stmt->bind_param("sii", $status, $id_menu, $id_owner);

        if ($stmt->execute()) {
            header("Location: 3_owner.php");
            exit();
        } else {
            die("Error executing query: " . $stmt->error);
        }
    } else {
        die("Data tidak valid: " . 
            "\nID Menu: " . var_export($id_menu, true) .
            "\nStatus: " . var_export($status, true) .
            "\nID Owner: " . var_export($id_owner, true));
    }
} else {
    die("Invalid request method");
}
?>