<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

// Ambil semua pesanan dan gabungkan berdasarkan id_user + waktu_pesan
$query = mysqli_query($conn, "
    SELECT p.id_user, u.nama AS nama_user, p.id_update_ad, p.waktu_pesan, ua.status,
           p.id AS id_pesanan, mj.nama_jus, mj.harga, p.jumlah, p.pakai_es, p.pakai_gula
    FROM pesanan p
    JOIN users u ON p.id_user = u.id
    JOIN menujus mj ON p.id_menu = mj.id
    JOIN update_ad ua ON p.id_update_ad = ua.id
    ORDER BY p.waktu_pesan DESC
");

// Proses update status
if (isset($_POST['update_status'])) {
    $id_pesanan = intval($_POST['id_pesanan']);

    // Update status ke paid
    mysqli_query($conn, "UPDATE pesanan SET id_update_ad = 2 WHERE id = $id_pesanan");

    // Ambil data pesanan untuk hitung total bayar
    $result = mysqli_query($conn, "
        SELECT p.jumlah, mj.harga
        FROM pesanan p
        JOIN menujus mj ON p.id_menu = mj.id
        WHERE p.id = $id_pesanan
    ");
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $total_bayar = $data['jumlah'] * $data['harga'];

        // Simpan ke tabel invoice
        mysqli_query($conn, "
            INSERT INTO invoice (id_pesanan, total_bayar)
            VALUES ($id_pesanan, $total_bayar)
        ");
    }

    // Redirect ulang halaman
    header("Location: daftar_pesanan.php");
    exit;
}


// Kelompokkan berdasarkan kombinasi id_user + waktu_pesan
$pesanan_per_group = [];

while ($row = mysqli_fetch_assoc($query)) {
    $group_key = $row['id_user'] . '|' . $row['waktu_pesan'];

    if (!isset($pesanan_per_group[$group_key])) {
        $pesanan_per_group[$group_key] = [
            'nama' => $row['nama_user'],
            'waktu_pesan' => $row['waktu_pesan'],
            'pesanan' => []
        ];
    }

    $pesanan_per_group[$group_key]['pesanan'][] = $row;
}
?>

<h2>Kasir - Daftar Pesanan Pelanggan</h2>

<style>
.card {
    border: 1px solid #ccc;
    padding: 15px;
    margin: 15px 0;
    border-radius: 8px;
    background: #f9f9f9;
}
.status {
    font-weight: bold;
    color: #d9534f;
}
.status.paid {
    color: green;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th, td {
    padding: 8px;
    border: 1px solid #aaa;
    text-align: center;
}
</style>

<?php if (empty($pesanan_per_group)): ?>
    <p><em>Belum ada pesanan</em></p>
<?php else: ?>
    <?php foreach ($pesanan_per_group as $group): ?>
        <?php $total_semua = 0; ?>
        <div class="card">
            <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($group['nama']) ?></p>
            <p><strong>Waktu Pesan:</strong> <?= $group['waktu_pesan'] ?></p>
            <table> <!-- menampilkan data pesanan yang masuk dari berbagai user-->
                <tr>
                    <th>ID Pesanan</th>
                    <th>Nama Jus</th>
                    <th>Jumlah</th>
                    <th>Es</th>
                    <th>Gula</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>                
                <?php foreach ($group['pesanan'] as $pesanan): ?>
                    <?php //menghitung total tagihan pesanan
                        $subtotal = $pesanan['harga'] * $pesanan['jumlah'];
                        $total_semua += $subtotal;
                    ?>
                    <tr>
                        <td><?= $pesanan['id_pesanan'] ?></td>
                        <td><?= htmlspecialchars($pesanan['nama_jus']) ?></td>
                        <td><?= $pesanan['jumlah'] ?></td>
                        <td><?= $pesanan['pakai_es'] ? '✔️' : '❌' ?></td>
                        <td><?= $pesanan['pakai_gula'] ? '✔️' : '❌' ?></td>
                        <td>Rp<?= number_format($pesanan['harga'], 0, ',', '.') ?></td>
                        <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td> 
                        <!-- mengupdate status pesanan jika sudah dibayar-->
                        <td class="<?= $pesanan['status'] === 'paid' ? 'status paid' : 'status' ?>">
                            <?= strtoupper($pesanan['status']) ?>
                        </td>
                        <td>
                            <?php if ($pesanan['status'] === 'unpaid'): ?>
                                <form method="POST"> <!-- mengubah status berdarkan id_pesanan-->
                                    <input type="hidden" name="id_pesanan" value="<?= $pesanan['id_pesanan'] ?>">
                                    <button type="submit" name="update_status">Tandai PAID</button>
                                </form>
                            <?php else: ?>
                               
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="6" align="right"><strong>Total:</strong></td>
                    <td colspan="3"><strong>Rp<?= number_format($total_semua, 0, ',', '.') ?></strong></td>
                </tr>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
