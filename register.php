<?php
session_start();
include 'connfigs/connect.php';

$err = '';
if(isset($_POST['register'])){
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];

    if(!$nama || !$email || !$password){
        $err = 'Nama, email, dan password wajib diisi.';
    } elseif($password !== $pass_confirm){
        $err = 'Password tidak sama.';
    } else {
        // cek email exist
        $stmt = mysqli_prepare($conn, "SELECT id FROM pasien WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) > 0){
            $err = 'Email sudah dipakai.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO pasien (nama, email, password, no_hp) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $hash, $phone);
            if(mysqli_stmt_execute($stmt)){
                // ambil id dan login otomatis
                $id = mysqli_insert_id($conn);
                $_SESSION['pasien'] = ['id' => $id, 'nama' => $nama, 'email' => $email];
                header("Location: login.php");
                exit;
            } else {
                $err = 'Gagal daftar. Coba lagi.';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Daftar - HnR Dental</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body{
    font-family:Inter, sans-serif; 

    /* 💜 Soft purple futuristic gradient (lebih terang dari sebelumnya) */
    background: linear-gradient(180deg,#3b1e7a 0%, #2b235a 40%, #1f1b45 100%);
  }

  /* glass effect lebih terang */
  .glass{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.18);
  }

  /* input putih tapi sedikit soft */
  .input-gloss{
    background:#f4f4f6;
    border-radius:12px;
    padding:0.65rem 0.85rem;
    border:1px solid rgba(0,0,0,0.08);
    color:#1a1a1a;
  }

  h1, h2 {
    text-shadow: 0 0 8px rgba(255,255,255,0.17);
  }
</style>

</head>
<body class="min-h-screen flex items-center justify-center text-white p-6">
  <div class="max-w-3xl w-full grid lg:grid-cols-2 gap-8 items-center">
    <!-- left - branding -->
    <div class="hidden lg:block">
      <div class="p-8 rounded-2xl glass">
  <h1 class="text-3xl font-extrabold text-purple-200 mb-2">HnR Dental</h1>
  <p class="text-white/70">Yuk daftar, konsultasi cepet & aman. UI ungu futuristik tapi gak ribet.</p>
        <div class="mt-6">
          <div class="text-sm text-white/70">Keuntungan:</div>
          <ul class="mt-3 text-white/70 list-disc list-inside">
            <li>Jawaban langsung dari admin</li>
            <li>Riwayat konsultasi tersimpan</li>
            <li>Data aman & terenkripsi</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- right - form -->
    <div class="bg-white/6 p-8 rounded-2xl glass">
  <h2 class="text-2xl font-bold mb-2">Buat Akun Pasien (Gratis!)</h2>
  <p class="text-sm text-white/70 mb-4">Isi data biar bisa nanya ke admin, gampang kok.</p>

      <?php if($err): ?>
        <div class="bg-red-600/40 text-white px-4 py-2 rounded mb-4"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" class="space-y-4">
        <label class="block text-sm">Nama
          <input class="w-full input-gloss" type="text" name="nama" required placeholder="Nama lengkap" value="<?= isset($_POST['nama'])?htmlspecialchars($_POST['nama']):'' ?>">
        </label>

        <label class="block text-sm">Email
          <input class="w-full input-gloss" type="email" name="email" required placeholder="email@contoh.com" value="<?= isset($_POST['email'])?htmlspecialchars($_POST['email']):'' ?>">
        </label>

        <label class="block text-sm">No HP (opsional)
          <input class="w-full input-gloss" type="text" name="phone" placeholder="contoh: +62812xxxx" value="<?= isset($_POST['phone'])?htmlspecialchars($_POST['phone']):'' ?>">
        </label>

        <div class="grid grid-cols-2 gap-3">
          <label class="block text-sm">Password
            <input class="w-full input-gloss" type="password" name="password" required>
          </label>
          <label class="block text-sm">Konfirmasi Password
            <input class="w-full input-gloss" type="password" name="password_confirm" required>
          </label>
        </div>

        <div class="flex items-center justify-between gap-4">
          <a href="../index.php" class="text-sm text-white/70 hover:underline">Balik</a>
          <button type="submit" name="register" class="ml-auto bg-gradient-to-r from-[#4c1d95] to-[#9333ea] px-6 py-2 rounded-full font-semibold shadow">Daftar</button>
        </div>
      </form>
      <p class="text-sm text-white/70 mt-4">Udah punya akun? <a href="login.php" class="text-purple-200 underline">Masuk</a></p>
    </div>
  </div>
</body>
</html>
