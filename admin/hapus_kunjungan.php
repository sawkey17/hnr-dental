<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>

<?php

include '../connfigs/connect.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']); // biar aman

    // hapus dokter berdasar ID
    $query = "DELETE FROM kunjungan WHERE id_kunjungan = $id";

    if(mysqli_query($conn, $query)){
        echo "<script>alert('kunjungan berhasil dihapus!'); window.location='kunjungan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kunjungan!'); window.location='kunjungan.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location='kunjungan.php';</script>";
}
?>
