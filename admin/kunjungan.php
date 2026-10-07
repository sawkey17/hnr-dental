<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>

<?php
include '../connfigs/connect.php';

// Ambil data kunjungan + join pasien & dokter biar keliatan nama mereka
$query = mysqli_query($conn, "
  SELECT 
    k.id_kunjungan, 
    k.nama_pasien, 
    d.nama_dokter, 
    k.tanggal, 
    k.jam, 
    k.keluhan, 
    k.status
  FROM kunjungan k
  LEFT JOIN dokter d ON k.id_dokter = d.id
  ORDER BY k.tanggal DESC, k.jam DESC
");

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Kunjungan Dokter</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">
  <!-- Sidebar -->
  <?php include '../includes/sidebar.php'; ?>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Kunjungan Dokter</h1>

    <div class="bg-white shadow rounded-xl p-6 mb-8">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Kunjungan</h2>
        <a href="tambah_kunjungan.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
          + Tambah Kunjungan
        </a>
      </div>

      <table class="min-w-full border-collapse">
        <thead>
          <tr class="bg-blue-50 text-blue-700">
            <th class="text-left py-3 px-4">ID Kunjungan</th>
            <th class="text-left py-3 px-4">Pasien</th>
            <th class="text-left py-3 px-4">Dokter</th>
            <th class="text-left py-3 px-4">Tanggal</th>
            <th class="text-left py-3 px-4">Jam</th>
            <th class="text-left py-3 px-4">Keluhan</th>
            <th class="text-left py-3 px-4">Status</th>
            <th class="text-center py-3 px-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($query)) { ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="py-3 px-4"><?= $row['id_kunjungan'] ?></td>
            <td class="py-3 px-4 font-medium text-gray-800"><?= $row['nama_pasien'] ?></td>
            <td class="py-3 px-4"><?= $row['nama_dokter'] ?></td>
            <td class="py-3 px-4"><?= $row['tanggal'] ?></td>
            <td class="py-3 px-4"><?= $row['jam'] ?></td>
            <td class="py-3 px-4"><?= $row['keluhan'] ?></td>

            <td class="py-3 px-4">
                <span class="px-2 py-1 rounded text-sm
                    <?= $row['status'] == 'Selesai'
                        ? 'bg-green-100 text-green-700'
                        : ($row['status'] == 'Proses'
                            ? 'bg-yellow-100 text-yellow-700'
                            : ($row['status'] == 'Cancel'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-gray-100 text-gray-700')) ?>">
                    <?= $row['status'] ?>
                </span>
            </td>
            <td class="py-3 px-4 text-center">
              <a href="edit_kunjungan.php?id_kunjungan=<?= $row['id_kunjungan'] ?>" 
                 class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
              <a href="hapus_kunjungan.php?id_kunjungan=<?= $row['id_kunjungan'] ?>" 
                 onclick="return confirm('Yakin hapus data kunjungan ini?')" 
                 class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
