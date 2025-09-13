<?php
include '../includes/header.php';
include '../config/db.php';

// mengambil daa id user dari session
$id_user = $_SESSION['id'] ?? null;
if (!$id_user) {
    echo "Silakan login terlebih dahulu.";
    exit();
}

// Ambil isi keranjang user
$query = "
    SELECT k.id, mj.nama_jus, k.jumlah, k.pakai_es, k.pakai_gula, mj.harga
    FROM keranjang k
    JOIN menujus mj ON k.id_menu = mj.id
    WHERE k.id_user = $id_user
";
$result = mysqli_query($conn, $query);
$total_semua = 0;
?>

<h2>Keranjang Anda</h2>
<!-- tampilan tabel pada keranjang, pastikan ada data pada databse keranjang-->
<?php if (mysqli_num_rows($result) > 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Nama Jus</th>
            <th>Jumlah</th>
            <th>Es</th>
            <th>Gula</th>
            <th>Harga Satuan</th>
            <th>SubTotal</th>
            <th>Aksi</th>
        </tr> <!-- menghitung total berdasarkan harga yang dikali dengan jumlah jus  -->
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php 
                $subtotal = $row['harga'] * $row['jumlah'];
                $total_semua += $subtotal; //menghitung total tagihan pesanan dengan menjumlahkan subtotal
            ?>
            <tr> <!-- menampilkan detail pesanan -->
                <td><?= htmlspecialchars($row['nama_jus']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['pakai_es'] ? 'V' : 'X' ?></td>
                <td><?= $row['pakai_gula'] ? 'V' : 'X' ?></td>
                <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                <td> <!-- konfirmasi pengguna untuk menghapus data pesanan pada keranjang -->
                    <form method="POST" action="hapus_keranjang.php" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                        <input type="hidden" name="id_keranjang" value="<?= $row['id'] ?>">
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        <tr> 
            <td colspan="5" align="right"><strong>Total:</strong></td>
            <td colspan="2"><strong>Rp<?= number_format($total_semua, 0, ',', '.') ?></strong></td>
        </tr>
    </table>
    <br> <!-- memastikan user menyelsaikan pesanannya -->
    <form method="POST" action="selesaikan_pesanan.php" onsubmit="return confirm('Yakin ingin selesaikan semua pesanan?');">
        <button type="submit">Selesaikan Pesanan</button>
    </form>
<?php else: ?>
    <p>Keranjang Anda kosong.</p>
<?php endif; ?>

<a href="beranda.php">← Kembali ke Menu</a>

