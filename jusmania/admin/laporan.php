<?php
include '../config/db.php';
include '../includes/header.php';

// Ambil tanggal dari form (default: hari ini)
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

// Query rekap untuk tanggal yang dipilih
$query = mysqli_query($conn, "
    SELECT DATE(waktu_pesan) AS tanggal, 
           COUNT(*) AS jumlah_pesanan,
           SUM(mj.harga * p.jumlah) AS total_penghasilan
    FROM pesanan p
    JOIN menujus mj ON p.id_menu = mj.id
    WHERE DATE(waktu_pesan) = '$tanggal'
    GROUP BY DATE(waktu_pesan)
");
$data = mysqli_fetch_assoc($query);

// Query total penghasilan keseluruhan
$total_query = mysqli_query($conn, "
    SELECT SUM(mj.harga * p.jumlah) AS total_keseluruhan
    FROM pesanan p
    JOIN menujus mj ON p.id_menu = mj.id
");
$total_data = mysqli_fetch_assoc($total_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan</title>     <!-- Import library Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f5f5f5;
        }
        h2 {
            color: #333;
        }
        a {
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
        }
        form {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        input[type="date"] {
            padding: 5px;
            font-size: 14px;
        }
        button {
            padding: 6px 12px;
            font-size: 14px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .summary {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        canvas {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<a href="dashboard.php">🔙 Dashboard Admin</a>

<h2>Laporan Pesanan</h2>
<!-- Form memilih tanggal dan menampilkan laporan pesanan untuk tanggal -->
<form method="GET">
    <label for="tanggal">📅 Pilih Tanggal:</label>
    <input type="date" name="tanggal" id="tanggal" value="<?= $tanggal ?>">
    <button type="submit">Tampilkan</button>
</form>

<div class="summary">
    <?php if ($data): ?>
        <h3>🗓️ Tanggal: <?= htmlspecialchars($tanggal) ?></h3>
        <p><strong>Jumlah Pesanan:</strong> <?= $data['jumlah_pesanan'] ?></p>
        <p><strong>Total Penghasilan:</strong> Rp<?= number_format($data['total_penghasilan'], 0, ',', '.') ?></p>
    <?php else: ?> <!-- Jika tidak ada pesanan, tampilkan pesan-->
        <p><em>Tidak ada pesanan untuk tanggal ini.</em></p>
    <?php endif; ?>
<!--Menampilkan total penghasilan semua waktu-->
    <?php if ($total_data): ?>
        <hr>
        <p><strong>💰 Total Penghasilan Keseluruhan:</strong> Rp<?= number_format($total_data['total_keseluruhan'], 0, ',', '.') ?></p>
    <?php endif; ?>
</div>

<canvas id="grafikHarian" width="600" height="250"></canvas>
<!-- Ambil data JSON dari PHP -->
<script>
fetch('grafik_data.php')
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById('grafikHarian'), {
            type: 'line',
            data: {
                labels: data.tanggal,
                datasets: [{
                    label: 'Penghasilan Harian',
                    data: data.penghasilan,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp' + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
