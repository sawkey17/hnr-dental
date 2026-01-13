<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
include '../connfigs/connect.php';

// cek login
$isLogin = isset($_SESSION['user']);
$id_pasien = $isLogin ? $_SESSION['user']['id'] : NULL;
$nama_login = $isLogin ? $_SESSION['user']['nama'] : '';
$email_login = $isLogin ? $_SESSION['user']['email'] : '';

$error = "";
$success = "";


 
// Submit form
if(isset($_POST['daftar'])){

    // === NAMA & EMAIL ===
    if($isLogin){
        // auto dari session
        $nama = $nama_login;
        $email = $email_login;
    } else {
        // input manual
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);

        if(empty($nama) || empty($email)){
            $error = "Nama & email wajib diisi!";
        }
    }

    // === DATA LAIN ===
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $keluhan = trim($_POST['keluhan']);

    if(empty($tanggal) || empty($jam) || empty($keluhan)){
        $error = "Semua data wajib diisi.";
    }

    // === SIMPAN ===
    if(!$error){

        $stmt = mysqli_prepare($conn,
            "INSERT INTO kunjungan (id_pasien, nama_pasien, email_pasien, id_dokter, tanggal, jam, keluhan, status)
             VALUES (?, ?, ?, NULL, ?, ?, ?, 'belum datang')"
        );

        mysqli_stmt_bind_param($stmt, "isssss",
            $id_pasien,   // kalau login = id, kalo tidak login = NULL
            $nama,
            $email,
            $tanggal,
            $jam,
            $keluhan
        );

        if(mysqli_stmt_execute($stmt)){
            $success = "Kunjungan berhasil didaftarkan!";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi Kunjungan - HnR Dental</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family: Inter, sans-serif;
    background: linear-gradient(180deg,#31255A,#251E4A,#1F1B45);
    padding: 30px;
    color:white;
}
.card{
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border:1px solid rgba(255,255,255,0.25);
}
.input-box{
    background:white;
    color:black;
    padding:12px;
    border-radius:10px;
    width:100%;
}
</style>
</head>

<body class="flex justify-center">

<div class="card w-full max-w-lg p-8 rounded-xl shadow-xl">

    <h2 class="text-3xl font-bold text-center mb-6">Registrasi Kunjungan</h2>

    <?php if($error): ?>
        <div class="bg-red-600/40 text-white px-4 py-2 rounded mb-4 text-center">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="bg-green-600/40 text-white px-4 py-2 rounded mb-4 text-center">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">

        <!-- Nama Pasien -->
        <div>
            <label class="text-sm">Nama Pasien</label>
            <input type="text" name="nama"
                   class="input-box"
                   placeholder="Masukkan nama"
                   value="<?= $isLogin ? $nama_login : '' ?>"
                   <?= $isLogin ? 'readonly' : 'required' ?>>
        </div>

        <!-- Email Pasien -->
        <div>
            <label class="text-sm">Email Pasien</label>
            <input type="email" name="email"
                   class="input-box"
                   placeholder="Masukkan email"
                   value="<?= $isLogin ? $email_login : '' ?>"
                   <?= $isLogin ? 'readonly' : 'required' ?>>
        </div>

        <!-- Tanggal -->
        <div>
            <label class="text-sm">Tanggal Kunjungan</label>
            <input type="date" name="tanggal" class="input-box" required>
        </div>

        <!-- Jam -->
        <div>
            <label class="text-sm">Jam</label>
            <input type="time" name="jam" class="input-box" required>
        </div>

        <!-- Keluhan -->
        <div>
            <label class="text-sm">Keluhan</label>
            <textarea name="keluhan" class="input-box h-28" required 
                      placeholder="Ceritakan keluhanmu"></textarea>
        </div>

        <button name="daftar"
                class="w-full py-3 bg-white text-purple-700 font-semibold rounded-lg mt-2">
            Daftar Kunjungan
        </button>
    </form>

    <p class="text-center mt-4 text-white/80">
        Kembali ke dashboard?
        <a href="dashboard.php" class="text-purple-300 underline">Klik disini</a>
    </p>

</div>

</body>
</html>
