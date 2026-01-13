<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}
?>


<?php
include '../connfigs/connect.php';

// ambil id_konsultasi dari URL
$id_konsultasi = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ambil data konsultasi
$dataResult = mysqli_query($conn, "
    SELECT * FROM konsultasi WHERE id_konsultasi = $id_konsultasi
");

$data = mysqli_fetch_assoc($dataResult);

// jika data kosong → tampilkan error elegan
if(!$data){
    die("<h2 style='padding:20px; font-family:Arial;'>Data konsultasi tidak ditemukan.</h2>");
}

// jika submit jawaban
if(isset($_POST['submit'])){
    $jawaban = mysqli_real_escape_string($conn, $_POST['jawaban']);

    mysqli_query($conn, "
        UPDATE konsultasi 
        SET jawaban = '$jawaban', status = 'selesai'
        WHERE id_konsultasi = $id_konsultasi
    ");

    echo "<script>alert('Jawaban berhasil dikirim!'); window.location='konsultasi.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jawab Konsultasi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

<div class="max-w-xl mx-auto bg-white p-6 shadow rounded-xl">
  <h1 class="text-2xl font-bold mb-4">Jawab Konsultasi</h1>

  <p><strong>Topik:</strong> <?= htmlspecialchars($data['topik']) ?></p>

  <p class="mb-4"><strong>Catatan:</strong> 
      <?= nl2br(htmlspecialchars($data['catatan'])) ?>
  </p>

  <form method="post">
    <textarea name="jawaban" 
              class="border p-3 w-full rounded-lg mb-4"
              placeholder="Tulis jawaban admin..." required></textarea>

    <button type="submit" name="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
      Kirim Jawaban
    </button>
  </form>
</div>

</body>
</html>
