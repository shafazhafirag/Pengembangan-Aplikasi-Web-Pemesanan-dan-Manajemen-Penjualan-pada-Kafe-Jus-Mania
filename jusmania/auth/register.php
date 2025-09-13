<?php

include '../config/db.php';
// menambahkan data pada database nama, username, dan password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash password pakai MD5
    $hash = md5($password);

    // Default role = 3 (pelanggan)
    $idrole = 3;

    // Cek apakah username sudah digunakan
    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah terdaftar'); window.location.href='register.php';</script>";
    } else {
        $query = "INSERT INTO users (nama, username, password, idrole) VALUES ('$nama', '$username', '$hash', $idrole)";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal.'); window.location.href='register.php';</script>";
        }
    }
}
?>

<!-- tampilan halaman Form -->
<h2>Form Register</h2>
<form method="POST">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Daftar</button>
</form>
