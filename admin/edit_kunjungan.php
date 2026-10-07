
<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../connfigs/connect.php';

// Cek ID kunjungan
if (!isset($_GET['id_kunjungan'])) {
    header("Location: kunjungan.php");
    exit;
}

$id_kunjungan = intval($_GET['id_kunjungan']);

// Ambil data kunjungan
$result = mysqli_query(
    $conn,
    "SELECT * FROM kunjungan WHERE id_kunjungan = $id_kunjungan"
);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>
            alert('Data kunjungan tidak ditemukan!');
            window.location='kunjungan.php';
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Update status
if (isset($_POST['update'])) {

    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = mysqli_query(
        $conn,
        "UPDATE kunjungan
         SET status = '$status'
         WHERE id_kunjungan = $id_kunjungan"
    );

    if ($update) {
        echo "<script>
                alert('Status kunjungan berhasil diubah!');
                window.location='kunjungan.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal mengubah status kunjungan!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kunjungan</title>

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #0b0c10, #101820);
            color: #f5f5f5;

            padding: 60px;

            display: flex;
            justify-content: center;
            align-items: center;

            min-height: 100vh;
            margin: 0;

            box-sizing: border-box;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.05);

            padding: 30px 40px;

            border-radius: 16px;

            box-shadow: 0 0 25px rgba(0, 120, 255, 0.2);

            border: 1px solid rgba(255, 255, 255, 0.1);

            width: 400px;
        }

        h2 {
            color: #d4af37;

            margin-bottom: 25px;

            text-align: center;

            text-shadow: 0 0 8px rgba(212, 175, 55, 0.6);
        }

        label {
            display: block;

            margin-bottom: 8px;

            font-weight: 600;
        }

        select {
            width: 100%;

            box-sizing: border-box;

            background: rgba(255, 255, 255, 0.1);

            border: 1px solid rgba(255, 255, 255, 0.2);

            color: #fff;

            padding: 10px;

            border-radius: 8px;

            font-size: 14px;

            margin-bottom: 20px;

            outline: none;
        }

        select:focus {
            border-color: #00bfff;

            box-shadow: 0 0 8px rgba(0, 191, 255, 0.5);
        }

        select option {
            background: #101820;
            color: white;
        }

        .button-container {
            display: flex;
            gap: 10px;
        }

        button,
        .cancel {
            box-sizing: border-box;

            padding: 10px 18px;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            text-decoration: none;

            font-weight: 600;

            text-align: center;
        }

        button {
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: #fff;

            flex: 1;
        }

        button:hover {
            background: linear-gradient(135deg, #00aaff, #33ccff);

            box-shadow: 0 0 12px rgba(0, 191, 255, 0.6);
        }

        .cancel {
            background: #444;
            color: #fff;

            flex: 1;
        }

        .cancel:hover {
            background: #555;
        }
    </style>
</head>

<body>

<div class="form-container">

    <h2>Edit Status Kunjungan</h2>

    <form method="POST">

        <label for="status">
            Status Kunjungan
        </label>


<select name="status" id="status" required>

    <option value="Proses"
        <?= $data['status'] == 'Proses' ? 'selected' : '' ?>>
        Proses
    </option>

    <option value="Selesai"
        <?= $data['status'] == 'Selesai' ? 'selected' : '' ?>>
        Selesai
    </option>

    <option value="Cancel"
        <?= $data['status'] == 'Cancel' ? 'selected' : '' ?>>
        Cancel
    </option>

</select>


        <div class="button-container">

            <button type="submit" name="update">
                Simpan Perubahan
            </button>

            <a href="kunjungan.php" class="cancel">
                Batal
            </a>

        </div>

    </form>

</div>

</body>

</html>

