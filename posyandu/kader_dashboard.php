<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'Kader') {
    header('Location: /posyandu/index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT b.id, b.nama, b.jenis_kelamin, b.umur, 
           c.berat_badan, c.tinggi_badan
    FROM bayi b
    LEFT JOIN (
        SELECT id_bayi, berat_badan, tinggi_badan
        FROM catatan_pertumbuhan
        WHERE id IN (
            SELECT MAX(id)
            FROM catatan_pertumbuhan
            GROUP BY id_bayi
        )
    ) c ON b.id = c.id_bayi
    WHERE b.id_kader = ?
");
$stmt->execute([$_SESSION['id_pengguna']]);
$bayi_list = $stmt->fetchAll();

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM bayi WHERE id = ? AND id_kader = ?");
    $stmt->execute([$id, $_SESSION['id_pengguna']]);
    header('Location: /posyandu/kader_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kader</title>
</head>
<body>
    <h2>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?> (Kader)</h2>
    <a href="/posyandu/tambah_bayi.php">Tambah Bayi</a> |
    <a href="/posyandu/logout.php">Keluar</a>
    <h2>Daftar Bayi</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>Berat Badan (kg)</th>
                <th>Tinggi Badan (cm)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bayi_list as $bayi) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($bayi['nama']); ?></td>
                    <td><?php echo htmlspecialchars($bayi['jenis_kelamin']); ?></td>
                    <td><?php echo htmlspecialchars($bayi['umur']); ?></td>
                    <td><?php echo $bayi['berat_badan'] ? htmlspecialchars($bayi['berat_badan']) : '-'; ?></td>
                    <td><?php echo $bayi['tinggi_badan'] ? htmlspecialchars($bayi['tinggi_badan']) : '-'; ?></td>
                    <td>
                        <a href="/posyandu/tambah_bayi.php?id=<?php echo $bayi['id']; ?>">Ubah</a>
                        <a href="/posyandu/tambah_pertumbuhan.php?id_bayi=<?php echo $bayi['id']; ?>">Tambah Pertumbuhan</a>
                        <a href="/posyandu/kader_dashboard.php?hapus=<?php echo $bayi['id']; ?>" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>