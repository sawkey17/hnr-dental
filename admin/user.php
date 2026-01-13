 <?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>
 <?php
include '../connfigs/connect.php';
$query = mysqli_query($conn, "SELECT * FROM pasien");
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Users</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">
  <!-- Sidebar -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-6">Manajemen Users</h1>

    <div class="bg-white shadow rounded-xl p-6 mb-8">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Users</h2>
        <a href="tambah_pasien.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">+ Tambah Pasien</a>
      </div>

      <table class="min-w-full border-collapse">
        <thead>
          <tr class="bg-blue-50 text-blue-700">
            <th class="text-left py-3 px-4">ID</th>
            <th class="text-left py-3 px-4">Nama</th>
            <th class="text-left py-3 px-4">Jenis Kelamin</th>
            <th class="text-left py-3 px-4">Umur</th>
            <th class="text-left py-3 px-4">No HP</th>
            <th class="text-left py-3 px-4">Alamat</th>
            <th class="text-center py-3 px-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($query)) { ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="py-3 px-4"><?= $row['id'] ?></td>
            <td class="py-3 px-4 font-semibold"><?= $row['nama'] ?></td>
            <td class="py-3 px-4"><?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            <td class="py-3 px-4"><?= $row['umur'] ?></td>
            <td class="py-3 px-4"><?= $row['no_hp'] ?></td>
            <td class="py-3 px-4"><?= $row['alamat'] ?></td>
            <td class="py-3 px-4 text-center">
              <a href="edit_pasien.php?id=<?= $row['id'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
              <a href="hapus_pasien.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus pasien ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
