<?php
session_start();
include '../connfigs/connect.php';

// CEK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$id_pasien = $_SESSION['user']['id'];
$nama_pasien = $_SESSION['user']['nama'] ?? '';

// =======================
//  ACTION: CANCEL KUNJUNGAN
// =======================
if (isset($_GET['batal'])) {
    $id_kj = intval($_GET['batal']);
    // ENUM values: 'belum datang', 'selesai', 'cancel', ''
    $result = mysqli_query($conn, "UPDATE kunjungan SET status='cancel' WHERE id_kunjungan=$id_kj");
    if ($result && mysqli_affected_rows($conn) > 0) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// =======================
//  ACTION: SELESAI KUNJUNGAN
// =======================
if (isset($_GET['selesai'])) {
    $id_kj = intval($_GET['selesai']);
    $result = mysqli_query($conn, "UPDATE kunjungan SET status='selesai' WHERE id_kunjungan=$id_kj");
    if ($result && mysqli_affected_rows($conn) > 0) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// =======================
// Cek kolom id_pasien konsultasi
// =======================
$has_id_pasien = mysqli_num_rows(mysqli_query($conn, "SHOW COLUMNS FROM konsultasi LIKE 'id_pasien'")) > 0;

if ($has_id_pasien) {
    $where_kons = "id_pasien = " . intval($id_pasien);
} else {
    $escaped = mysqli_real_escape_string($conn, $nama_pasien);
    $where_kons = "nama_pasien = '$escaped'";
}

// TOTAL KONSULTASI
$q1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM konsultasi WHERE $where_kons");
$total_konsultasi = ($q1 ? mysqli_fetch_assoc($q1)['total'] : 0);

// TOTAL KUNJUNGAN
$q2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kunjungan WHERE id_pasien = $id_pasien");
$total_kunjungan = ($q2 ? mysqli_fetch_assoc($q2)['total'] : 0);

// RIWAYAT KONSULTASI
$riwayat = mysqli_query($conn, "SELECT * FROM konsultasi WHERE $where_kons ORDER BY tanggal DESC LIMIT 5");

// RIWAYAT KUNJUNGAN
$q_kunjungan = mysqli_query($conn,
    "SELECT * FROM kunjungan 
     WHERE id_pasien = $id_pasien
     ORDER BY tanggal DESC, jam DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Pasien - HnR</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
    body{
        font-family: Inter, sans-serif;
        background: linear-gradient(180deg, #31255A 0%, #251E4A 45%, #1F1B45 100%);
        color: #F5F3FF;
    }

    .glass{
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.20);
    }

    nav{
        background: rgba(255,255,255,0.10);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.18);
    }

    .card{
        transition: .25s;
    }
    .card:hover{
        background: rgba(255,255,255,0.18);
        transform: translateY(-3px);
    }

    .title{
        color:#E9D5FF;
    }
</style>
</head>
<body class="p-6">

<!-- NAVBAR -->
<nav class="max-w-6xl mx-auto mb-10 p-4 rounded-2xl flex justify-between items-center">
    <h1 class="text-xl font-bold text-purple-200">HnR Dental</h1>

    <div class="flex gap-6 text-sm">
        <a href="../index.php" class="text-white/90 hover:text-purple-200">Home</a>
        <a href="dashboard.php" class="text-white/90 hover:text-purple-200">Dashboard</a>
        <a href="konsultasi.php" class="text-white/90 hover:text-purple-200">Konsultasi</a>
        <a href="../logout.php" class="px-4 py-1.5 bg-red-500/80 hover:bg-red-600 rounded-full">Logout</a>
    </div>
</nav>

<div class="max-w-6xl mx-auto">

    <h2 class="text-3xl font-bold mb-6">Halo, <?= $nama_pasien ?> 👋</h2>

    <!-- CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <div class="glass p-6 rounded-2xl card">
            <div class="title font-semibold">Total Konsultasi</div>
            <div class="text-4xl font-bold mt-2"><?= $total_konsultasi ?></div>
        </div>

        <div class="glass p-6 rounded-2xl card">
            <div class="title font-semibold">Total Kunjungan</div>
            <div class="text-4xl font-bold mt-2"><?= $total_kunjungan ?></div>
        </div>

        <div class="glass p-6 rounded-2xl card">
            <div class="title font-semibold">Status Akun</div>
            <div class="text-2xl mt-2 text-green-300 font-bold">Aktif</div>
        </div>

    </div>

    <!-- RIWAYAT KONSULTASI -->
    <h3 class="text-xl font-semibold mb-4">Riwayat Konsultasi Terbaru</h3>

    <div class="space-y-4">
        <?php if(mysqli_num_rows($riwayat) == 0): ?>
            <div class="glass p-4 rounded-xl text-white/80">Belum ada riwayat.</div>
        <?php else: ?>
            <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
                <div class="glass p-4 rounded-xl card">
                    <div class="flex justify-between">
                        <div>
                            <div class="font-semibold text-purple-200 text-lg"><?= htmlspecialchars($r['topik'] ?? 'N/A'); ?></div>
                            <div class="text-white/70 text-sm"><?= $r['tanggal'] ?? ''; ?></div>
                        </div>

                        <?php if(($r['status'] ?? '') == 'selesai'): ?>
                            <span class="px-3 py-1 bg-green-500/30 text-green-200 rounded-full text-sm">Selesai</span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-yellow-500/30 text-yellow-200 rounded-full text-sm">Menunggu</span>
                        <?php endif; ?>
                    </div>

                    <div class="text-white/90 text-sm mt-3 leading-relaxed">
                        <?= nl2br(htmlspecialchars($r['catatan'] ?? '')) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- JADWAL KUNJUNGAN -->
    <h3 class="text-xl font-semibold mt-10 mb-4">Jadwal Kunjungan Kamu</h3>

    <div class="space-y-4">
        <?php if(mysqli_num_rows($q_kunjungan) == 0): ?>
            <div class="glass p-4 rounded-xl text-white/80">Belum punya jadwal kunjungan.</div>
        <?php else: ?>
            <?php while($k = mysqli_fetch_assoc($q_kunjungan)): ?>
                <?php
                    // Format tanggal — contoh: 2025-02-25 -> 25 Feb 2025
                    $tgl_fix = date("d M Y", strtotime($k['tanggal'] ?? ''));
                    // Format jam — contoh: 02:34:00.000000 -> 02:34
                    $jam_fix = date("H:i", strtotime($k['jam'] ?? ''));
                ?>
                <div class="glass p-4 rounded-xl card">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-semibold text-purple-200 text-lg"><?= $tgl_fix ?> — <?= $jam_fix ?></div>
                            <div class="text-white/70 text-sm mt-1">Keluhan: <?= nl2br($k['keluhan'] ?? ''); ?></div>
                        </div>
                        <div class="text-right ml-4">
                            <?php if(($k['status'] ?? '') == 'cancel'): ?>
                                <span class="px-3 py-1 bg-red-500/30 text-red-300 rounded-full text-sm">Batal</span>
                            <?php elseif(($k['status'] ?? '') == 'selesai'): ?>
                                <span class="px-3 py-1 bg-green-500/30 text-green-200 rounded-full text-sm">Selesai</span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-yellow-500/30 text-yellow-200 rounded-full text-sm block mb-2">Belum Datang</span>
                                <div class="flex flex-col gap-2 text-sm">
                                    <a href="dashboard.php?batal=<?= $k['id_kunjungan'] ?? '' ?>" class="bg-red-500/40 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs text-center" onclick="return confirm('Yakin mau batalkan?')">Batalkan</a>
                                    <?php
                                        $now = time();
                                        $deadline = strtotime(($k['tanggal'] ?? '')." ".($k['jam'] ?? '')." +3 hours");
                                        if($now >= $deadline):
                                    ?>
                                        <a href="dashboard.php?selesai=<?= $k['id_kunjungan'] ?? '' ?>" class="bg-green-500/40 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs text-center">Sudah Selesai?</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>
