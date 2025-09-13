<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

// Cek apakah user adalah admin
if ($_SESSION['role'] != 4) {
    header("Location: ../auth/login.php");
    exit();
}
// hapus menu


// Handle tambah menu
if (isset($_POST['tambah'])) {
    $nama_jus = $_POST['nama_jus'];
    $id_bahan = $_POST['id_bahan'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Cek apakah nama jus sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM menujus WHERE nama_jus = '$nama_jus'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Nama jus sudah tersedia!";
    } else {
        mysqli_query($conn, "INSERT INTO menujus (nama_jus, id_bahan, harga, deskripsi) VALUES ('$nama_jus', '$id_bahan', '$harga', '$deskripsi')");
        $pesan = "Menu berhasil ditambahkan.";
    }
}

// Ambil data bahan
$bahan = mysqli_query($conn, "SELECT * FROM bahan");

// Ambil semua menu jus
$menu = mysqli_query($conn, "
    SELECT m.id, m.nama_jus, b.nama_bahan, m.harga, m.deskripsi
    FROM menujus m
    JOIN bahan b ON m.id_bahan = b.id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Menu Jus - Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
        input, select, textarea, button { padding: 8px; margin: 5px 0; width: 100%; }
        form { margin-top: 20px; max-width: 500px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body> <!-- Tombol kembali ke Dashboard -->
    <a href="dashboard.php">Dasboard Admin</a>
    <h2>Kelola Menu Jus</h2>
 <!-- Menampilkan pesan sukses/gagal -->
    <?php if (isset($pesan)) echo "<p class='".(strpos($pesan, 'berhasil') ? "success" : "error")."'>$pesan</p>"; ?>
   <!-- Form untuk menambahkan menu jus baru -->
    <form method="POST">
        <label>Nama Jus</label>
        <input type="text" name="nama_jus" required>

        <label>Bahan Utama</label>
        <select name="id_bahan" required>
            <option value="">-- Pilih Bahan --</option>
            <?php while ($b = mysqli_fetch_assoc($bahan)) : ?>
                <!-- Menampilkan daftar bahan dari database -->
                <option value="<?= $b['id'] ?>"><?= $b['nama_bahan'] ?> (<?= $b['jumlah'].' '.$b['satuan'] ?>)</option>
            <?php endwhile; ?>
        </select>
        <!-- Input data untuk menabakan di database -->
        <label>Harga</label>
        <input type="number" name="harga" step="0.01" required>
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="3"></textarea>

        <button type="submit" name="tambah">Tambahkan</button>
    </form>
<!-- Daftar semua menu jus yang sudah ada -->
    <h3>Daftar Menu Jus</h3>
    <table>
        <tr>
            <th>Nama Jus</th>
            <th>Bahan Utama</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>  <!-- Menampilkan data menu jus dari database -->
        <?php while ($m = mysqli_fetch_assoc($menu)) : ?>
        <tr>
            <td><?= $m['nama_jus'] ?></td>
            <td><?= $m['nama_bahan'] ?></td>
            <td>Rp<?= number_format($m['harga'], 2, ',', '.') ?></td>
            <td><?= $m['deskripsi'] ?></td>
            

        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
