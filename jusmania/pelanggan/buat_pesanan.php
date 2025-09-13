<?php
include '../includes/header.php';
include '../config/db.php';

// mengambil id sesuai data menu yang diambil
$id_user = $_SESSION['id'];
$id_menu = intval($_POST['id_menu']);

// Ambil data menu
$query_menu = mysqli_query($conn, "SELECT * FROM menujus WHERE id = $id_menu");
if (!$query_menu || mysqli_num_rows($query_menu) == 0) {
    echo "<p>Menu tidak ditemukan.</p>";
    include '../includes/footer.php';
    exit();
}
$menu = mysqli_fetch_assoc($query_menu);

// Ambil stok bahan es dan gula
$query_es = mysqli_query($conn, "SELECT * FROM bahan WHERE nama_bahan LIKE '%es%' LIMIT 1");
$es = mysqli_fetch_assoc($query_es);

$query_gula = mysqli_query($conn, "SELECT * FROM bahan WHERE nama_bahan LIKE '%gula%' LIMIT 1");
$gula = mysqli_fetch_assoc($query_gula);
?>
<!-- menampilkan nama jus berdasarkan id sebelumnya -->
<h2>Pesan Jus: <?= htmlspecialchars($menu['nama_jus']) ?></h2>
<!-- tombol untuk menambilkan menu beranda -->
<a href="beranda.php">Kembali ke Beranda</a> 
<br><br>
<!-- menampilkan form untuk memesan jus | Menjalankan perinta pada tambah_keranjang.php-->
<form method="POST" action="tambah_keranjang.php">
    <input type="hidden" name="id_menu" value="<?= $menu['id'] ?>">

    <p><strong>Harga:</strong> Rp<?= number_format($menu['harga'], 0, ',', '.') ?></p>
    <!-- membuat custom jus dengan es dan gula periksa stok bahan juga-->
    <label><input type="checkbox" name="pakai_es" value="1" <?= ($es && $es['jumlah'] <= 0 ? 'disabled' : '') ?>>
        Pakai Es <?= ($es && $es['jumlah'] <= 0 ? '(Stok habis)' : '') ?>
    </label><br>
    <label><input type="checkbox" name="pakai_gula" value="1" <?= ($gula && $gula['jumlah'] <= 0 ? 'disabled' : '') ?>>
        Pakai Gula <?= ($gula && $gula['jumlah'] <= 0 ? '(Stok habis)' : '') ?>
    </label><br><br>
    <!-- untuk memesan jumlah jus yang diinginkan -->
    <label>Jumlah:</label>
    <input type="number" name="jumlah" value="1" min="1" required><br><br>

    <button type="submit">Tambahkan ke Keranjang</button>
</form>

