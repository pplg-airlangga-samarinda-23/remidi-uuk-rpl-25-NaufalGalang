<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'Admin') {
    header('Location: index.php');
    exit;
}

$kader = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = ? AND peran = 'Kader'");
    $stmt->execute([$_GET['id']]);
    $kader = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $nama_pengguna = isset($_POST['nama_pengguna']) ? trim($_POST['nama_pengguna']) : '';
    $kata_sandi = isset($_POST['kata_sandi']) ? trim($_POST['kata_sandi']) : '';
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (empty($nama) || empty($nama_pengguna) || empty($kata_sandi)) {
        $error = "Semua field harus diisi.";
    } else {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE pengguna SET nama = ?, nama_pengguna = ?, kata_sandi = ? WHERE id = ? AND peran = 'Kader'");
                $stmt->execute([$nama, $nama_pengguna, $kata_sandi, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO pengguna (nama, nama_pengguna, kata_sandi, peran) VALUES (?, ?, ?, 'Kader')");
                $stmt->execute([$nama, $nama_pengguna, $kata_sandi]);
            }
            header('Location: admin_dashboard.php');
            exit;
        } catch (PDOException $e) {
            $error = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $kader ? 'Ubah Kader' : 'Tambah Kader'; ?></title>
</head>
<style>
        body {
    font-family: Arial;
    background: #f3e5f5;
    margin: 20px;
}

h2 {
    color:rgb(0, 0, 0);
}

form {
    width: 250px;
}

label {
    color:rgb(195, 0, 255); 
}

input, select {
    width: 100%;
    padding: 5px;
    margin: 5px 0;
    border: 1px solid rgb(195, 0, 255); 
}

input[type="submit"] {
    background: #7b1fa2;
    color: white;
    border: none;
    padding: 5px;
}

a {
    color: #7b1fa2;
}
    </style>
<body>
    <h2><?php echo $kader ? 'Ubah Kader' : 'Tambah Kader'; ?></h2>
    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo isset($kader['id']) ? htmlspecialchars($kader['id']) : ''; ?>">
        <div>
            <label for="nama">Nama</label><br>
            <input type="text" id="nama" name="nama" value="<?php echo isset($kader['nama']) ? htmlspecialchars($kader['nama']) : ''; ?>" required>
        </div>
        <div>
            <label for="nama_pengguna">Nama Pengguna</label><br>
            <input type="text" id="nama_pengguna" name="nama_pengguna" value="<?php echo isset($kader['nama_pengguna']) ? htmlspecialchars($kader['nama_pengguna']) : ''; ?>" required>
        </div>
        <div>
            <label for="kata_sandi">Kata Sandi</label><br>
            <input type="password" id="kata_sandi" name="kata_sandi" required>
        </div>
        <button type="submit">Simpan</button>
        <a href="admin_dashboard.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>