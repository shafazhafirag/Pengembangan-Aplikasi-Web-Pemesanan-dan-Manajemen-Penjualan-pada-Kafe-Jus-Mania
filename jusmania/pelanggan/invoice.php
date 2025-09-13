<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

// meulai session dnegan mengambil data user id
$id_user = $_SESSION['id'] ?? 0;
if (!$id_user) {
    echo "Anda belum login.";
    exit();
}

// Ambil nama user
$user_result = mysqli_query($conn, "SELECT nama FROM users WHERE id = $id_user");
$user = mysqli_fetch_assoc($user_result);
$nama_user = $user['nama'] ?? 'Tidak Diketahui';

// Ambil semua pesanan user beserta harga jus
$query = "
    SELECT p.id AS id_pesanan, p.id_menu, p.waktu_pesan, mj.nama_jus, mj.harga,
           p.jumlah, ua.status, p.pakai_es, p.pakai_gula
    FROM pesanan p
    JOIN menujus mj ON p.id_menu = mj.id
    JOIN update_ad ua ON p.id_update_ad = ua.id
    WHERE p.id_user = $id_user
    ORDER BY p.waktu_pesan DESC
";

$result = mysqli_query($conn, $query) or die("Query Error: " . mysqli_error($conn));

// Kelompokkan pesanan berdasarkan waktu_pesan
$grouped = [];
while ($row = mysqli_fetch_assoc($result)) {
    $grouped[$row['waktu_pesan']][] = $row;
}
?>

<h2>Daftar Invoice Pesanan</h2>
<!-- jika data kosong-->
<?php if (empty($grouped)): ?>
    <p>Belum ada pesanan.</p>
<?php else: ?> <!-- kelompokan berdasarkan waktu pemesanan-->
    <?php foreach ($grouped as $waktu => $items): ?>
        <?php $total_semua = 0; ?>
        <div style="border: 2px solid #ccc; margin-bottom: 20px; padding: 15px;">
            <h3>Invoice</h3> <!-- mengambil data berdasarkan idpesanan yang sama dengan session id user-->
            <p><strong>Nama:</strong> <?= htmlspecialchars($nama_user) ?></p>
            <p><strong>Waktu Pesan:</strong> <?= $waktu ?></p>
            <p><strong>No. Antrian:</strong> <?= $items[0]['id_pesanan'] ?></p>

            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>Nama Jus</th>
                    <th>Jumlah</th>
                    <th>Es</th>
                    <th>Gula</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <?php  // menghitung total tagihan pesanan
                        $subtotal = $item['harga'] * $item['jumlah'];
                        $total_semua += $subtotal;
                    ?>
                    <tr>
                <!-- menampilkan detail pesanan yang sudah dibayar dengan live status pesanan-->
                        <td><?= htmlspecialchars($item['nama_jus']) ?></td>
                        <td><?= $item['jumlah'] ?></td>
                        <td><?= $item['pakai_es'] ? '✔️' : '❌' ?></td>
                        <td><?= $item['pakai_gula'] ? '✔️' : '❌' ?></td>
                        <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                        <td><b style="color:red;"><?= htmlspecialchars($item['status']) ?></b></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" align="right"><strong>Total:</strong></td>
                    <td colspan="2"><strong>Rp<?= number_format($total_semua, 0, ',', '.') ?></strong></td>
                </tr>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<!-- tombol untuk menampilkan halaman beranda-->
<a href="beranda.php">⬅️ Kembali ke Menu</a>
