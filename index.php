<?php
session_start();
include 'connfigs/connect.php';

// Jika yang login admin, lempar ke dashboard admin
if(isset($_SESSION['user'])){
    if(($_SESSION['user']['role'] ?? '') === 'admin'){
        header("Location: admin/dashboard.php");
        exit;
    }
}

// HITUNG STATISTIK (pangkas duplikasi dan pastikan semua variabel ada)
$total_pasien = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pasien"))['total'];
$total_konsultasi = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM konsultasi"))['total'];
$total_selesai = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM konsultasi WHERE status='selesai'"))['total'];
$total_menunggu = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM konsultasi WHERE status='menunggu'"))['total'];

// total kunjungan (kalau tabelnya ada)
$cek_kunjungan = mysqli_query($conn, "SHOW TABLES LIKE 'kunjungan'");
if (mysqli_num_rows($cek_kunjungan) > 0) {
    $kunjungan = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kunjungan"))['total'];
} else {
    $kunjungan = 0;
}

// beberapa alias supaya bagian HTML lama tetep jalan (biar gak banyak ubah)
$pasien = $total_pasien;
$konsultasi = $total_konsultasi;
$konsultasi_selesai = $total_selesai;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>HnR Dental — Landing</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>

.bubble {
    pointer-events: auto;
}


  body{
    font-family: "Inter", sans-serif;
    background: radial-gradient(circle at 10% 10%, #0ea5e9 0%, transparent 25%),
                radial-gradient(circle at 90% 90%, #7c3aed 0%, transparent 25%),
                #071029;
  }

  .blob{position:absolute;width:420px;height:420px;filter:blur(60px);opacity:0.6;mix-blend-mode:screen;border-radius:48%;}
  
  /* ===== navbar blur ===== */
  .navbar-blur{
    backdrop-filter: blur(14px);
    background: rgba(255,255,255,0.09);
    border-bottom: 1px solid rgba(255,255,255,0.15);
    transition: all .25s ease;
  }
  .navbar-clear{
    background: transparent;
    border-bottom: 1px solid transparent;
    transition: all .25s ease;
  }

  /* pointer events fix (navbar tetap bisa klik, bubble gak kehalang) */
  #navbar{ pointer-events:none; }
  #navbar *{ pointer-events:auto; }

  /* statistic card anim */
  .card-enter{ transform: translateY(16px); opacity: 0; transition: all .6s cubic-bezier(.2,.9,.3,1);}
  .card-enter.show{ transform:none; opacity:1;}

  /* typed caret */
  .caret{ display:inline-block; width:2px; height:1em; background:#fff; margin-left:6px; animation:blink 1s infinite;}
  @keyframes blink{50%{opacity:0;}}

  /* glass */
  .glass{
    background: rgba(255,255,255,0.04);
    border:1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(8px);
  }

  /* bubble fix 100% klik */
  .bubble{
      position: absolute;
      display:flex;
      align-items:center;
      justify-content:center;
      border-radius:999px;
      font-weight:700;
      color:#fff;
      cursor:pointer;
      z-index:100;
      transition:transform 5s linear;
  }



  /* keep navbar highest */
  #navbar{
    z-index:10000 !important;
}
</style>
</head>

<body class="text-white antialiased">

<!-- NAVBAR -->
<nav id="navbar" class="fixed w-full z-40 navbar-clear">
  <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-blue-300 font-bold">HnR</div>
          <div>
              <div class="text-lg font-bold">HnR-Dental</div>
              <div class="text-xs text-white/60 -mt-0.5">Klinik Digital</div>
          </div>
      </div>

      <div class="hidden md:flex items-center gap-6 text-sm">
        <a href="index.php">Home</a>
        <a href="pasien/konsultasi.php">Konsultasi</a>
        <a href="pasien/regristasi.php">Registrasi</a>
        <a href="pasien/dashboard.php" class="hover:text-blue-600">Dashboard</a>
        <a href="logout.php" class="hover:text-red-500">Log out</a>

        <?php if(isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'pasien'): ?>
            <span class="px-3 py-1 rounded-full bg-white/10"><?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
        <?php else: ?>
            <a href="login.php" class="bg-blue-500 px-4 py-2 rounded-lg font-semibold">Login</a>
        <?php endif; ?>
      </div>

      <div class="md:hidden">
        <?php if(isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'pasien'): ?>
            <span class="px-3 py-1 rounded-full bg-white/10 text-sm"><?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
        <?php else: ?>
            <a href="login.php" class="bg-blue-500 px-3 py-2 rounded-lg text-sm">Login</a>
        <?php endif; ?>
      </div>
  </div>
</nav>

  

<!-- HERO -->
<header class="pt-28 pb-16 relative overflow-hidden">
  <!-- Blobs -->
  <div class="blob" style="left:-12%; top:-8%; background:linear-gradient(135deg,#06b6d4,#60a5fa); animation: float1 10s infinite alternate;"></div>
  <div class="blob" style="right:-10%; bottom:-6%; background:linear-gradient(135deg,#7c3aed,#f97316); animation: float2 11s infinite alternate;"></div>

  <div class="max-w-6xl mx-auto px-6 relative z-10">
      <div class="grid lg:grid-cols-2 gap-10 items-center">
        
          <div>
              <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">
                Pelayanan Kesehatan Modern & Terpercaya
              </h1>

              <div class="flex items-center gap-2 text-xl mb-3">
                <p id="typedText"></p>
                <span class="caret"></span>
              </div>

              <p class="text-white/75 max-w-xl mb-6">
                  Konsultasi cepat, aman, dan mudah. Jawaban langsung dari admin klinik.
              </p>

              <div class="flex gap-4">
                  <a href="pasien/regristasi.php" class="bg-white text-blue-700 px-5 py-3 rounded-lg font-semibold shadow-md">Daftar Sekarang</a>
                  <a href="pasien/konsultasi.php" class="border border-white/20 px-5 py-3 rounded-lg">Konsultasi</a>
              </div>
          </div>

          <!-- Stats -->
          <div class="grid grid-cols-2 gap-4">
              <div class="glass p-5 rounded-xl card-enter" data-target="<?= $total_pasien ?>">
                  <div class="text-sm text-white/70">Total Pasien</div>
                  <div class="text-4xl font-bold stat">0</div>
              </div>
              <div class="glass p-5 rounded-xl card-enter" data-target="<?= $total_konsultasi ?>">
                  <div class="text-sm text-white/70">Total Konsultasi</div>
                  <div class="text-4xl font-bold stat">0</div>
              </div>
              <div class="glass p-5 rounded-xl card-enter" data-target="<?= $total_selesai ?>">
                  <div class="text-sm text-white/70">Selesai</div>
                  <div class="text-4xl font-bold stat">0</div>
              </div>
              <div class="glass p-5 rounded-xl card-enter" data-target="<?= $total_menunggu ?>">
                  <div class="text-sm text-white/70">Menunggu</div>
                  <div class="text-4xl font-bold stat">0</div>
              </div>
          </div>
      </div>
  </div>
</header>

<section class="py-10">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-purple-500 text-white rounded-xl p-6 shadow-md flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Total Pasien</p>
                <h2 class="text-4xl font-bold"><?= $pasien ?></h2>
            </div>
            <span class="text-3xl">🧑‍⚕️</span>
        </div>

        <div class="bg-indigo-500 text-white rounded-xl p-6 shadow-md flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Total Konsultasi</p>
                <h2 class="text-4xl font-bold"><?= $konsultasi ?></h2>
            </div>
            <span class="text-3xl">💬</span>
        </div>

        <div class="bg-emerald-500 text-white rounded-xl p-6 shadow-md flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Konsultasi Selesai</p>
                <h2 class="text-4xl font-bold"><?= $konsultasi_selesai ?></h2>
            </div>
            <span class="text-3xl">✔️</span>
        </div>

        <div class="bg-pink-500 text-white rounded-xl p-6 shadow-md flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Total Kunjungan</p>
                <h2 class="text-4xl font-bold"><?= $kunjungan ?></h2>
            </div>
            <span class="text-3xl">📅</span>
        </div>

    </div>
</section>


<!-- Doctors -->
<section class="max-w-7xl mx-auto px-8 pb-18">
  <h2 class="text-2xl font-semibold mb-6">Dokter Unggulan</h2>

  <div class="grid md:grid-cols-2 gap-6">

    <div class="glass p-6 rounded-xl flex items-center gap-5">
      <img src="assets/doknum.png" class="w-24 h-24 rounded-full object-cover">
      <div>
        <div class="font-bold text-xl">Drg. Rahmat R</div>
        <div class="text-sm text-white/70 mt-1">Dentist</div>
      </div>
    </div>

    <div class="glass p-6 rounded-xl flex items-center gap-5">
      <img src="assets/dokmat.png" class="w-24 h-24 rounded-full object-cover">
      <div>
        <div class="font-bold text-xl">Dr. Hanum L</div>
        <div class="text-sm text-white/70 mt-1">Dentist</div>
      </div>
    </div>

  </div>
</section>

<footer class="py-10 text-center text-white/60">
  © <?= date('Y') ?> HnR Dental
</footer>

<script>
/* ---------------------------------------
   NAVBAR BLUR SCROLL
--------------------------------------- */
const nav = document.getElementById("navbar");
window.addEventListener("scroll", () => {
    if (window.scrollY > 20) {
        nav.classList.remove("navbar-clear");
        nav.classList.add("navbar-blur");
    } else {
        nav.classList.remove("navbar-blur");
        nav.classList.add("navbar-clear");
    }
});

/* ---------------------------------------
   TYPEWRITER TEXT
--------------------------------------- */
const phrases = [
  "Selamat Datang di HnR Dental",
  "Konsultasi Mudah & Cepat",
  "Jawaban Langsung dari Admin",
  "Data Aman & Rahasia"
];
let ti=0, pi=0;
function typeLoop(){
  const el = document.getElementById("typedText");
  const text = phrases[ti];
  el.innerText = text.slice(0,pi++);
  if(pi > text.length){ pi=0; ti=(ti+1)%phrases.length; setTimeout(typeLoop,700); }
  else setTimeout(typeLoop,42);
}
typeLoop();

/* ---------------------------------------
   BUBBLE GAME GIGI 🦷
--------------------------------------- */

function spawnBubble(){
  const b = document.createElement("div");
  b.className = "bubble";

  const size = 55 + Math.floor(Math.random() * 30);
  b.style.width = b.style.height = size + "px";

  const posX = 5 + Math.random() * 85;
  b.style.left = posX + "vw";
  b.style.bottom = "-60px";

  b.style.background = "transparent";
  b.innerHTML = `<span style="font-size:${size * 0.6}px">🦷</span>`;

  document.body.appendChild(b);

  const drift = (Math.random() * 2 - 1) * 40;
  requestAnimationFrame(() => {
    b.style.transform = `translate(${drift}px, -85vh)`;
  });

  const timeout = setTimeout(() => b.remove(), 5200);

  b.addEventListener("mousedown", () => {
    b.style.transition = "transform .25s, opacity .25s";
    b.style.transform = "scale(0.2)";
    b.style.opacity = "0";

    setTimeout(() => b.remove(), 250);
    clearTimeout(timeout);
  });
}

/* Spawn bubble */
let spawnRate = window.innerWidth < 700 ? 1200 : 900;
setInterval(spawnBubble, spawnRate);

/* Kill bubble before reaching navbar */
function killBubbleIfTooHigh(){
  document.querySelectorAll(".bubble").forEach(b=>{
    const r = b.getBoundingClientRect();
    if(r.top < 85){ b.remove(); }
  });
}
setInterval(killBubbleIfTooHigh, 100);

</script>
</body>
</html>
