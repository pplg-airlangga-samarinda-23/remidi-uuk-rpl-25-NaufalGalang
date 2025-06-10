<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = isset($_POST['nama_pengguna']) ? trim($_POST['nama_pengguna']) : '';
    $kata_sandi = isset($_POST['kata_sandi']) ? trim($_POST['kata_sandi']) : '';

    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE nama_pengguna = ? AND kata_sandi = ? AND peran = 'Admin'");
    $stmt->execute([$nama_pengguna, $kata_sandi]);
    $pengguna = $stmt->fetch();

    if ($pengguna) {
        $_SESSION['id_pengguna'] = $pengguna['id'];
        $_SESSION['peran'] = $pengguna['peran'];
        $_SESSION['nama'] = $pengguna['nama'];
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = "Nama pengguna atau kata sandi salah, atau Anda bukan Admin.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Posyandu</title>
</head>
<body>
    <h2>Login Admin</h2>
    <form method="POST">
        <div>
            <label for="nama_pengguna">Nama Pengguna</label><br>
            <input type="text" id="nama_pengguna" name="nama_pengguna" required>
        </div>
        <div>
            <label for="kata_sandi">Kata Sandi</label><br>
            <input type="password" id="kata_sandi" name="kata_sandi" required>
        </div>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <button type="submit">Masuk</button>
    </form>
    <a href="index.php"><button>Kembali</button></a>
</body>
</html>