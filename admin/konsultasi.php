
<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>

<?php
include '../connfigs/connect.php';

// Ambil data konsultasi + join nama pasien
$query = mysqli_query($conn, "
  SELECT 
      k.id_konsultasi,
      p.nama AS nama_pasien,
      k.tanggal,
      k.topik,
      k.catatan,
      k.jawaban,
      k.status
  FROM konsultasi k
  JOIN pasien p ON k.id_pasien = p.id
  ORDER BY k.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Konsultasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

  <?php include '../includes/sidebar.php'; ?>

  <main class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Konsultasi</h1>

    <div class="bg-white shadow rounded-xl p-6 mb-8">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Konsultasi</h2>
      </div>

      <table class="min-w-full border-collapse">
        <thead>
          <tr class="bg-blue-50 text-blue-700">
            <th class="text-left py-3 px-4">ID Konsultasi</th>
            <th class="text-left py-3 px-4">Pasien</th>
            <th class="text-left py-3 px-4">Tanggal</th>
            <th class="text-left py-3 px-4">Topik</th>
            <th class="text-left py-3 px-4">Catatan</th>
            <th class="text-left py-3 px-4">Status</th>
            <th class="text-center py-3 px-4">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php while($row = mysqli_fetch_assoc($query)) { ?>
          <tr class="border-b hover:bg-gray-50">
            
            <td class="py-3 px-4"><?= $row['id_konsultasi'] ?></td>
            <td class="py-3 px-4 font-medium text-gray-800"><?= $row['nama_pasien'] ?></td>
            <td class="py-3 px-4"><?= $row['tanggal'] ?></td>
            <td class="py-3 px-4"><?= $row['topik'] ?></td>
            <td class="py-3 px-4"><?= $row['catatan'] ?></td>

            <td class="py-3 px-4">
              <span class="px-2 py-1 rounded text-sm 
                <?= $row['status'] == 'selesai' ? 'bg-green-100 text-green-700' : 
                   ($row['status'] == 'menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') ?>">
                <?= $row['status'] ?>
              </span>
            </td>

            <td class="py-3 px-4 text-center">

              <a href="jawaban_konsultasi.php?id=<?= $row['id_konsultasi'] ?>" 
                 class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                 Jawab
              </a>

              <a href="hapus_konsultasi.php?id=<?= $row['id_konsultasi'] ?>" 
                 onclick="return confirm('Yakin hapus data konsultasi ini?')" 
                 class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                 Hapus
              </a>

            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
