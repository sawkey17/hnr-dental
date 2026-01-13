<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

?>
<?php include '../connfigs/connect.php'; ?>
<?php include '../includes/header.php'; ?>

<!-- Sidebar -->
<?php include '../includes/sidebar.php'; ?>


<!-- Main Content -->
<main class="flex-1 p-6 overflow-auto">
  <header class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Konsultasi Pasien</h1>
    <a href="dashboard.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg">⬅ Kembali</a>
  </header>

</main>

<?php include '../includes/footer.php'; ?>
