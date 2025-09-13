<?php
session_start();
include '../config/db.php';
include '../includes/header.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 4) {
    header("Location: ../auth/login.php");
    exit();
}

$pesan = '';
$error = '';
$edit_mode = false;
// hapus bahan
if (isset($_GET['deleteid']))  {
    $id = $_GET['deleteid'];
    $stmt = $conn->prepare("DELETE FROM bahan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: kelola_bahan.php");
    exit;
}

// Cek mode edit
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id_edit = $_GET['edit'];

    $stmt = $conn->prepare("SELECT * FROM bahan WHERE id = ?");
    $stmt->bind_param("i", $id_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    $bahan_edit = $result->fetch_assoc();
}

// Tambah bahan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_bahan']);
    $jumlah = $_POST['jumlah'];
    $satuan = $_POST['satuan'];

    // Cek nama bahan sudah ada
    $cek = $conn->prepare("SELECT * FROM bahan WHERE nama_bahan = ?");
    $cek->bind_param("s", $nama);
    $cek->execute();
    $cek_result = $cek->get_result();

    if ($cek_result->num_rows > 0) {
        $error = "Nama bahan '$nama' sudah ada!";
    } else {
        $stmt = $conn->prepare("INSERT INTO bahan (nama_bahan, jumlah, satuan) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $nama, $jumlah, $satuan);
        $stmt->execute();
        $pesan = "Bahan berhasil ditambahkan.";
    }
}

// Update bahan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = trim($_POST['nama_bahan']);
    $jumlah = $_POST['jumlah'];
    $satuan = $_POST['satuan'];

    // Cek nama bahan apakah sudah digunakan oleh id lain
    $cek = $conn->prepare("SELECT * FROM bahan WHERE nama_bahan = ? AND id != ?");
    $cek->bind_param("si", $nama, $id);
    $cek->execute();
    $cek_result = $cek->get_result();

    if ($cek_result->num_rows > 0) {
        $error = "Bahan '$nama' sudah digunakan oleh bahan lain!";
    } else {
        $stmt = $conn->prepare("UPDATE bahan SET nama_bahan=?, jumlah=?, satuan=? WHERE id=?");
        $stmt->bind_param("sdsi", $nama, $jumlah, $satuan, $id);
        $stmt->execute();
        $pesan = "Bahan berhasil diperbarui.";
        // redirect agar form kembali ke mode tambah
        header("Location: kelola_bahan.php");
        exit();
    }
}

// Ambil semua bahan
$bahan = $conn->query("SELECT * FROM bahan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Bahan - Jus Mania</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; margin-bottom: 30px; background: #f7f7f7; padding: 20px; border-radius: 10px; }
        input, select, button { width: 100%; margin-top: 10px; padding: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 10px; text-align: left; }
        a { padding: 4px 8px; background: #4caf50; color: white; text-decoration: none; border-radius: 4px; }
        .pesan { color: green; margin-bottom: 10px; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <!-- Link kembali ke Dashboard Admin -->
    <a href="dashboard.php">Dasboard Admin</a>

    <!-- mengubah mode form -->
    <h2><?= $edit_mode ? "Edit Bahan" : "Tambah Bahan" ?></h2>

    <!-- Menampilkan pesan jika berhasil atau error -->
    <?php if ($pesan): ?><p class="pesan"><?= $pesan ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

    <!-- Form Tambah/Edit Bahan -->
    <form method="POST">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $bahan_edit['id'] ?>">
        <?php endif; ?>

        <!-- Input data -->
        <input type="text" name="nama_bahan" placeholder="Nama bahan" required
            value="<?= $edit_mode ? $bahan_edit['nama_bahan'] : '' ?>">
        <input type="number" name="jumlah" placeholder="Jumlah" step="0.01" required
            value="<?= $edit_mode ? $bahan_edit['jumlah'] : '' ?>">
        <select name="satuan" required>
            <option value="">-- Pilih Satuan --</option>
            <?php
            $satuan_options = ['gram', 'ml', 'buah', 'kg', 'liter'];
            foreach ($satuan_options as $satuan) {
                $selected = ($edit_mode && $bahan_edit['satuan'] == $satuan) ? 'selected' : '';
                echo "<option value='$satuan' $selected>$satuan</option>";
            }
            ?>
        </select>

        <!-- Tombol mengubah mode -->
        <button type="submit" name="<?= $edit_mode ? 'update' : 'tambah' ?>">
            <?= $edit_mode ? 'Update' : 'Tambahkan' ?>
        </button>
    </form>

    <!-- Tabel daftar bahan -->
    <h2>Daftar Bahan</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Bahan</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>

        <!-- Menampilkan semua data bahan dari variabel $bahan -->
        <?php $no = 1; while ($row = $bahan->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama_bahan'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['satuan'] ?></td>
                <!-- Tombol Edit: kirim ID melalui parameter GET -->
                <td><a href="?edit=<?= $row['id'] ?>">Edit</a>
                <a href="?deleteid=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a></td>

            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

