<?php
session_start();
include '../config/db.php';
include '../includes/header.php';
// Tambah user baru
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $idrole = intval($_POST['idrole']);

    mysqli_query($conn, "INSERT INTO users (nama, username, password, idrole) VALUES ('$nama', '$username', '$password', $idrole)");
    header("Location: kelola_staff.php");
    exit;
}

// Hapus user
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: kelola_staff.php");
    exit;
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($res);
}

// Proses update user
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $idrole = intval($_POST['idrole']);

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        mysqli_query($conn, "UPDATE users SET nama='$nama', username='$username', password='$password', idrole=$idrole WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE users SET nama='$nama', username='$username', idrole=$idrole WHERE id=$id");
    }
    header("Location: kelola_staff.php");
    exit;
}

// Ambil semua data user (role: admin, kasir, dapur)
$result = mysqli_query($conn, "
    SELECT u.*, r.nama AS role_nama FROM users u 
    JOIN role r ON u.idrole = r.id 
    WHERE u.idrole IN (1, 2, 4)
    ORDER BY u.id ASC
");

$roles = mysqli_query($conn, "SELECT * FROM role WHERE id IN (1,2,4)");
?>

<h2>Kelola Staff</h2>
<!-- Link kembali ke dashboard admin -->
<a href="dashboard.php">Kembali ke Dashboard</a>

<h3><?= $edit_data ? 'Edit Staff' : 'Tambah Staff' ?></h3>
<form method="POST"> <!-- Form untuk menambah atau mengedit data staff -->
    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">
    <label>Nama:</label><br>
    <input type="text" name="nama" required value="<?= $edit_data['nama'] ?? '' ?>"><br>

    <label>Username:</label><br>
    <input type="text" name="username" required value="<?= $edit_data['username'] ?? '' ?>"><br>

    <label>Password:</label><br>
    <input type="password" name="password" <?= $edit_data ? '' : 'required' ?>><br>
    <?php if ($edit_data): ?><small>Kosongkan jika tidak ingin mengganti password</small><br><?php endif; ?>

    <label>Role:</label><br>
    <select name="idrole">
        <?php while ($r = mysqli_fetch_assoc($roles)): ?>
            <option value="<?= $r['id'] ?>" <?= ($edit_data && $edit_data['idrole'] == $r['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['nama']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit" name="<?= $edit_data ? 'update' : 'tambah' ?>">
        <?= $edit_data ? 'Update' : 'Tambah' ?> Staff
    </button>
</form>

<!-- Tabel untuk menampilkan semua staff dari database -->
<hr>
<h3>Daftar Staff</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Nama</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>  <!-- Loop setiap staff yang diambil dari database -->
    <?php while ($u = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($u['nama']) ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['role_nama']) ?></td>
            <td><!-- Tombol Edit: reload halaman dengan parameter ?edit -->
                <a href="kelola_staff.php?edit=<?= $u['id'] ?>">Edit</a> |
                 <!-- Tombol Hapus: reload halaman dengan parameter ?hapus dan konfirmasi -->
                <a href="kelola_staff.php?hapus=<?= $u['id'] ?>" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
