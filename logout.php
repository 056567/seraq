<?php
session_start();
session_destroy();
header("Location: 1_pelanggan.php"); // kembali ke halaman login
exit();
