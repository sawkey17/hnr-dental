<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
include '../connfigs/connect.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pasien') {
    header("Location: ../login.php");
    exit;
}

$id_pasien = $_SESSION['user']['id'];
$err = '';
$success = '';

if(isset($_POST['submit'])){
    $topik = trim($_POST['topik']);
    $catatan = trim($_POST['catatan']);
    if(!$topik || !$catatan){
        $err = 'Topik & catatan wajib diisi.';
    } else {
        $tanggal = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare($conn, "INSERT INTO konsultasi (id_pasien, tanggal, topik, catatan, status) VALUES (?, ?, ?, ?, 'menunggu')");
        mysqli_stmt_bind_param($stmt, "isss", $id_pasien, $tanggal, $topik, $catatan);
        if(mysqli_stmt_execute($stmt)){
            $success = 'Konsultasi terkirim. Tunggu jawaban admin.';
        } else {
            $err = 'Gagal mengirim. Coba lagi.';
        }
    }
}

// ambil 5 riwayat terbaru supaya pasien bisa lihat cepat
$riwayat = mysqli_query($conn, "SELECT * FROM konsultasi WHERE id_pasien = {$id_pasien} ORDER BY tanggal DESC LIMIT 8");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Konsultasi - HnR Dental</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  /* Background konsultasi: soft purple dusk (tidak gelap, tidak putih) */
  body{
      font-family:Inter, sans-serif; 
      background: linear-gradient(160deg,#d9d4ff,#c7bff9,#b298f7);
      color:#1e1b4b;
      min-height:100vh;
  }

  /* NAVBAR — lebih gelap sedikit supaya selalu keliatan */
  nav{
      background: rgba(255,255,255,0.25);
      backdrop-filter: blur(18px);
      border-radius: 14px;
      padding: 14px 20px;
      margin-bottom: 40px;
      border: 1px solid rgba(255,255,255,0.45);
      box-shadow:0 4px 18px rgba(0,0,0,0.07);
  }

  nav a{
      color:#3b1c77 !important;
      font-weight:600;
  }

  /* Card glass */
  .glass{
      background:rgba(255,255,255,0.65);
      backdrop-filter:blur(16px);
      border:1px solid rgba(255,255,255,0.8);
      box-shadow:0 4px 20px rgba(0,0,0,0.06);
      color:#1e1b4b;
  }

  /* Input */
  textarea.input-gloss, input.input-gloss{
      background:white;
      border-radius:12px;
      padding:0.8rem;
      border:1px solid rgba(0,0,0,0.08);
      color:#1e1b4b;
      box-shadow:0 1px 6px rgba(0,0,0,0.05);
  }

  /* Status bubble */
  .status-selesai{
      background:rgba(34,197,94,0.35);
      color:rgb(22,101,52);
  }
  .status-menunggu{
      background:rgba(245,158,11,0.35);
      color:rgb(180,83,9);
  }
</style>


</head>
<body class="min-h-screen p-6">
  <!-- simple navbar -->
  <nav class="max-w-6xl mx-auto flex items-center justify-between mb-8">
    <div class="text-white font-bold text-lg">HnR-Dental</div>
    <div class="flex gap-3">
       <a href="../index.php" class="text-white/80">homei</a>
      <a href="konsultasi.php" class="text-white/80">Konsultasi</a>
      <a href="dashboard.php" class="text-white/80">dashboard</a>
      <a href="logout.php" class="text-white/80">Logout</a>
    </div>
  </nav>

  <div class="max-w-4xl mx-auto grid lg:grid-cols-2 gap-8">
    <div class="bg-white/6 p-6 rounded-2xl glass">
      <h2 class="text-2xl font-bold mb-2">Kirim Konsultasi</h2>
      <p class="text-purple/70 mb-4">Tulis keluhan atau pertanyaanmu, admin bakal jawab.</p>

      <?php if($err): ?><div class="bg-red-600/40 text-white px-3 py-2 rounded mb-3"><?=htmlspecialchars($err)?></div><?php endif; ?>
      <?php if($success): ?><div class="bg-green-600/40 text-white px-3 py-2 rounded mb-3"><?=htmlspecialchars($success)?></div><?php endif; ?>

      <form method="post" class="space-y-4">
        <label class="block text-sm">Topik
          <input class="input-gloss" type="text" name="topik" placeholder="Contoh: Sakit gigi bagian belakang" required>
        </label>

        <label class="block text-sm">Catatan
          <textarea name="catatan" class="input-gloss" rows="6" placeholder="Jelaskan gejala, durasi, atau obat yang pernah dipakai" required></textarea>
        </label>

        <div class="flex justify-end">
          <button type="submit" name="submit" class="bg-gradient-to-r from-[#4c1d95] to-[#9333ea] px-6 py-2 rounded-full font-semibold">Kirim</button>
        </div>
      </form>
    </div>

    <div class="p-6 rounded-2xl glass">
      <h3 class="text-lg font-semibold mb-4">Riwayat Terbaru</h3>
      <?php if(mysqli_num_rows($riwayat) == 0): ?>
        <div class="text-white/70">Belum ada konsultasi.</div>
      <?php else: ?>
        <div class="space-y-4">
          <?php while($r = mysqli_fetch_assoc($riwayat)): ?>
            <div class="bg-white/5 p-4 rounded">
              <div class="flex justify-between items-start">
                <div>
                  <div class="font-semibold"><?=htmlspecialchars($r['topik'])?></div>
                  <div class="text-xs text-purple/60"><?=htmlspecialchars($r['tanggal'])?></div>
                </div>
                <div class="text-sm">
                  <?php if($r['status'] == 'selesai'): ?>
                    <span class="px-2 py-1 bg-green-700/30 rounded-full text-green-200">Selesai</span>
                  <?php else: ?>
                    <span class="px-2 py-1 bg-yellow-600/20 rounded-full text-yellow-200">Menunggu</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="mt-2 text-sm text.purple/80"><?= nl2br(htmlspecialchars($r['catatan'])) ?></div>
              <div class="mt-2 text-sm text.purple/80">
                <strong>Jawaban:</strong>
                <?php if($r['jawaban']): ?>
                  <div class="mt-1 bg.purple/6 p-3 rounded text-black/90"><?= nl2br(htmlspecialchars($r['jawaban'])) ?></div>
                <?php else: ?>
                  <div class="mt-1 text.purple/60"><i>Belum dijawab</i></div>
                <?php endif; ?>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
