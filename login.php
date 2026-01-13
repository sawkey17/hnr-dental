<?php
session_start();
include 'connfigs/connect.php';

$error = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $pass  = $_POST['password'];

    // Ambil user dari tabel pasien
    $q = mysqli_query($conn, "SELECT * FROM pasien WHERE email='$email' LIMIT 1");

    if(mysqli_num_rows($q) > 0){
        $u = mysqli_fetch_assoc($q);

        if(password_verify($pass, $u['password'])){
            // simpan data user di session
            $_SESSION['user'] = $u;

            // cek role admin / pasien
            if($u['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location:index.php");
            }
            exit;

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - HnR Dental</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">

<div class="bg-white p-8 rounded-2xl shadow-lg w-96">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label class="text-sm font-medium">Email</label>
        <input type="email" name="email" class="w-full p-3 mb-4 border rounded-lg focus:ring" required>

        <label class="text-sm font-medium">Password</label>
        <input type="password" name="password" class="w-full p-3 mb-4 border rounded-lg focus:ring" required>

        <button name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-semibold">
            Login
        </button>
    </form>

    <p class="text-center text-sm mt-4 text-gray-600">
        Belum punya akun? <a href="register.php" class="text-blue-600">Register</a>
    </p>
</div>

</body>
</html>
