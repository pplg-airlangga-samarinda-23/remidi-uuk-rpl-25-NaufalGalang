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
        body {
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    background-color: #f0f2f5;
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

p {
    color: #555;
    margin-bottom: 30px;
}

button {
    padding: 10px 20px;
    margin: 10px;
    font-size: 16px;
    color: #fff;
    background-color:rgb(195, 0, 255);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
     background-color:rgb(195, 0, 255);
}

a {
    text-decoration: none;
}
    </style>
</head>
<body>
    <h2>Selamat Datang di Aplikasi Posyandu</h2>
    <p>Pilih opsi login di bawah ini:</p>
    <a href="login_admin.php"><button>Login sebagai Admin</button></a>
    <a href="login_kader.php"><button>Login sebagai Kader</button></a>
</body>
</html>