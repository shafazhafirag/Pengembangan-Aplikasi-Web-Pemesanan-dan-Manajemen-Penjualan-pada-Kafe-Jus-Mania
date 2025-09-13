<?php
include '../config/db.php';

// Query untuk menghitung total penghasilan per hari
$result = mysqli_query($conn, "
    SELECT DATE(waktu_pesan) AS tanggal, 
           SUM(mj.harga * p.jumlah) AS penghasilan
    FROM pesanan p
    JOIN menujus mj ON p.id_menu = mj.id
    GROUP BY DATE(waktu_pesan)
    ORDER BY tanggal ASC
");
// Inisialisasi array untuk menampung hasil tanggal dan penghasilan
$tanggal = [];
$penghasilan = [];
// Loop hasil query dan masukkan ke array
while ($row = mysqli_fetch_assoc($result)) {
    $tanggal[] = $row['tanggal'];
    $penghasilan[] = $row['penghasilan'];
}

// Keluarkan data sebagai JSON
header('Content-Type: application/json');
echo json_encode(['tanggal' => $tanggal, 'penghasilan' => $penghasilan]);
