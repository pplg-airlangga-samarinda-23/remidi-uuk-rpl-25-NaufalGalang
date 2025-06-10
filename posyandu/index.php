<?php
session_start();

if (isset($_SESSION['peran'])) {
    error_log("Session peran: " . $_SESSION['peran']);
}

if (isset($_SESSION['peran'])) {
    if ($_SESSION['peran'] == 'Admin') {
        header('Location: admin_dashboard.php');
        exit;
    } elseif ($_SESSION['peran'] == 'Kader') {
        header('Location: kader_dashboard.php');
        exit;
    } else {
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Aplikasi Posyandu</title>
    <style>
    </style>
</head>
<body>
    <h2>Selamat Datang di Aplikasi Posyandu</h2>
    <p>Pilih opsi login di bawah ini:</p>
    <a href="login_admin.php"><button>Login sebagai Admin</button></a>
    <a href="login_kader.php"><button>Login sebagai Kader</button></a>
</body>
</html>