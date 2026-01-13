<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pasien'){
    header("Location: ../login.php");
    exit;
}
?>

<?php
include('../connfigs/connect.php');
include('../includes/header.php');
include('../includes/navbar-pasien.php');

$result = mysqli_query($conn, "SELECT * FROM dokter");
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<main class="flex-1 bg-gray-100 min-h-screen p-8">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Dokter</h1>

  <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-blue-600 text-white">
          <th class="p-3 text-left">Nama Dokter</th>
          <th class="p-3 text-left">Jadwal Praktek</th>
          <th class="p-3 text-left">No. HP</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr class="hover:bg-gray-50">
            <td class="p-3 font-semibold text-gray-800"><?= htmlspecialchars($row['nama_dokter']); ?></td>
            <td class="p-3 text-gray-700"><?= htmlspecialchars($row['jadwal_praktek']); ?></td>
            <td class="p-3 text-gray-700"><?= htmlspecialchars($row['no_hp']); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</main>

<?php include('../includes/footer.php'); ?>
