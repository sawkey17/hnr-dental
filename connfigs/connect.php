<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hnr-dental"; // ganti sesuai nama database lo di phpMyAdmin

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
