<?php
include '../includes/header.php';
include '../config/db.php';
// Ambil menu jus & ketersediaan bahan
$sql = "SELECT mj.id, mj.nama_jus, mj.harga, mj.deskripsi, b.jumlah
        FROM menujus mj 
        JOIN bahan b ON mj.id_bahan = b.id";
$result = $conn->query($sql);
?>
<!-- tombol untuk ke halaman keranjang -->
<a href="keranjang.php" style="display: flex; justify-content: flex-end;">keranjang</a>

<h2>Menu Jus</h2>
<!-- meriksa database terdapat data menu jus dan menampilkan data nama, harga desk-->
<?php if ($result->num_rows > 0): ?>     
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php while ($row = $result->fetch_assoc()): ?> <!-- mengatur tampilan menu jus-->
            <div style="border: 1px solid #ccc; padding: 15px; width: 250px;">
                <h3><?= htmlspecialchars($row['nama_jus']) ?></h3>
                <p><strong>Harga:</strong> Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                <!-- meriksa ketersedian bahan apakah bisa memesan jus-->
                <?php if ($row['jumlah'] > 0): ?>
                    <form method="POST" action="buat_pesanan.php">
                        <input type="hidden" name="id_menu" value="<?= $row['id'] ?>">                                                
                        <button type="submit">Pesan</button>                        
                    </form>
                    <!-- jika stok habis-->
                <?php else: ?>
                    <p style="color: red;">Stok bahan habis!</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div><!-- jika menu jus kosong-->
<?php else: ?>
    <p>Tidak ada menu jus tersedia.</p>
<?php endif; ?>
<!-- tombol untuk mengarah ke halaman invoice-->
<a href="invoice.php">Lihat Status Pesanan Anda</a>


