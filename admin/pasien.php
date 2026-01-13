<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>

<?php
include '../connfigs/connect.php';
include '../includes/header.php';
// sidebar/navigation for dokter pages
include '../includes/sidebar.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Konsultasi Pasien</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        h2 {
            color: #333;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Konsultasi Pasien</h2>
    <table>
        <tr>
            <th>Nama Pasien</th>
            <th>Keluhan</th>
            <th>Tanggal Konsultasi</th>
            <th>Status</th>
        </tr>
        <?php
        // ambil semua data konsultasi (use columns consistent with other pages)
        $result = mysqli_query($conn, "SELECT * FROM konsultasi ORDER BY tanggal DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            $tanggal = isset($row['tanggal']) ? date('d M Y', strtotime($row['tanggal'])) : '';
            $pertanyaan = isset($row['pertanyaan']) ? $row['pertanyaan'] : (isset($row['keluhan']) ? $row['keluhan'] : '');
            $status = isset($row['status']) ? $row['status'] : '';
            echo "
            <tr>
                <td>{$row['nama_pasien']}</td>
                <td>{$pertanyaan}</td>
                <td>{$tanggal}</td>
                <td>{$status}</td>
            </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
