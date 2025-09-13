<?php
include '../includes/header.php';
include '../config/db.php';

// Hitung total menu jus
$menu = $conn->query("SELECT COUNT(*) AS total FROM menujus")->fetch_assoc()['total'];

// Hitung total bahan
$bahan = $conn->query("SELECT COUNT(*) AS total FROM bahan")->fetch_assoc()['total'];

// Hitung total staff (kasir + dapur)
$staff = $conn->query("SELECT COUNT(*) AS total FROM users WHERE idrole IN (1, 2)")->fetch_assoc()['total'];

// Hitung total pesanan
$pesanan = $conn->query("SELECT COUNT(*) AS total FROM pesanan")->fetch_assoc()['total'];
?>

<h2>Dashboard Admin</h2>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 200px; padding: 15px; background-color: #e3f2fd; border-radius: 10px;">
        <h3>Total Menu Jus</h3>
        <p style="font-size: 24px;"><?= $menu ?></p>
        <a href="kelola_menu.php">Kelola menu</a>
    </div>

    <div style="flex: 1; min-width: 200px; padding: 15px; background-color: #fce4ec; border-radius: 10px;">
        <h3>Total Bahan Baku</h3>
        <p style="font-size: 24px;"><?= $bahan ?></p>
         <a href="kelola_bahan.php">Kelola Bahan</a>
    </div>

    <div style="flex: 1; min-width: 200px; padding: 15px; background-color: #fff9c4; border-radius: 10px;">
        <h3>Total Staff</h3>
        <p style="font-size: 24px;"><?= $staff ?></p>
        <a href="kelola_staff.php">Kelola Karyawan</a>
    </div>

    <div style="flex: 1; min-width: 200px; padding: 15px; background-color: #dcedc8; border-radius: 10px;">
        <h3>Total Pesanan</h3>
        <p style="font-size: 24px;"><?= $pesanan ?></p>
         <a href="laporan.php">Lihat Laporan</a>
    </div>
</div>

