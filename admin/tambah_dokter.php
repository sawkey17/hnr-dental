<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>

<?php
include '../connfigs/connect.php';

// kalau tombol submit diklik
if(isset($_POST['submit'])){
    $nama_dokter = mysqli_real_escape_string($conn, $_POST['nama_dokter']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $jadwal_praktek = mysqli_real_escape_string($conn, $_POST['jadwal_praktek']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $query = "INSERT INTO dokter (nama_dokter, email, jadwal_praktek, no_hp)
              VALUES ('$nama_dokter', '$email', '$jadwal_praktek', '$no_hp')";

    if(mysqli_query($conn, $query)){
        echo "<script>alert('Dokter berhasil ditambahkan!'); window.location='dokter.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan dokter!');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Dokter</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

  <!-- Sidebar -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Tambah Dokter</h1>

    <div class="bg-white shadow rounded-xl p-6 max-w-xl">

      <form method="post">

        <label class="block text-gray-700 font-semibold mb-2">Nama Dokter</label>
        <input type="text" name="nama_dokter" 
               class="w-full border p-3 rounded-lg mb-4" 
               placeholder="Nama lengkap" required>

        <label class="block text-gray-700 font-semibold mb-2">Email</label>
        <input type="email" name="email" 
               class="w-full border p-3 rounded-lg mb-4" 
               placeholder="emaildokter@example.com" required>

        <label class="block text-gray-700 font-semibold mb-2">Jadwal Praktek</label>
        <input type="text" name="jadwal_praktek" 
               class="w-full border p-3 rounded-lg mb-4" 
               placeholder="Contoh: Senin - Jumat, 08:00 - 16:00" required>

        <label class="block text-gray-700 font-semibold mb-2">No HP</label>
        <input type="text" name="no_hp" 
               class="w-full border p-3 rounded-lg mb-4" 
               placeholder="Nomor HP" required>

        <div class="flex justify-end gap-3">
          <a href="dokter.php" 
             class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Batal
          </a>
          <button type="submit" name="submit"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Simpan
          </button>
        </div>

      </form>

    </div>
  </main>
</body>
</html>
