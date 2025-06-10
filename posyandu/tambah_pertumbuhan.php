<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'Kader') {
    header('Location: /posyandu/index.php');
    exit;
}

if (!isset($_GET['id_bayi'])) {
    header('Location: /posyandu/kader_dashboard.php');
    exit;
}

$id_bayi = (int)$_GET['id_bayi'];
$stmt = $pdo->prepare("SELECT * FROM bayi WHERE id = ? AND id_kader = ?");
$stmt->execute([$id_bayi, $_SESSION['id_pengguna']]);
$bayi = $stmt->fetch();

if (!$bayi) {
    header('Location: /posyandu/kader_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $berat_badan = isset($_POST['berat_badan']) ? (float)$_POST['berat_badan'] : 0;
    $tinggi_badan = isset($_POST['tinggi_badan']) ? (float)$_POST['tinggi_badan'] : 0;

    if ($berat_badan <= 0 || $tinggi_badan <= 0) {
        $error = "Berat badan dan tinggi badan harus lebih dari 0.";
    } elseif ($berat_badan > 50 || $tinggi_badan > 200) {
        $error = "Berat badan atau tinggi badan tidak realistis.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO catatan_pertumbuhan (id_bayi, berat_badan, tinggi_badan) VALUES (?, ?, ?)");
            $stmt->execute([$id_bayi, $berat_badan, $tinggi_badan]);
            header('Location: /posyandu/kader_dashboard.php');
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
    <title>Catat Pertumbuhan</title>
</head>
<body>
    <h2>Catat Pertumbuhan untuk <?php echo htmlspecialchars($bayi['nama']); ?></h2>
    <?php if (isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST">
        <div>
            <label for="berat_badan">Berat Badan (kg)</label><br>
            <input type="number" step="0.1" id="berat_badan" name="berat_badan" required>
        </div>
        <div>
            <label for="tinggi_badan">Tinggi Badan (cm)</label><br>
            <input type="number" step="0.1" id="tinggi_badan" name="tinggi_badan" required>
        </div>
        <button type="submit">Simpan</button>
        <a href="/posyandu/kader_dashboard.php" class="button-link">Batal</a>
    </form>
</body>
</html>