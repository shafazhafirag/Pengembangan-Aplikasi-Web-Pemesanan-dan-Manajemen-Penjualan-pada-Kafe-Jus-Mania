<?php

// memulai sesi dan mengkoneksikan database
session_start();
include '../config/db.php';
// mengatur user masuk bedasarkan username dan password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // menggunakan tabel users pada database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    // jika username ditemukan
    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        // password hash tipe md5 membandingkan dengan databse
        if (md5($password) === $user['password']) {
            // login berhasil
            $_SESSION['id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['idrole'];

            // redirect sesuai role
            switch ($user['idrole']) {
                case 1: // sebagai dapur
                    header("Location: " . $base_url . "dapur/proses_pesanan.php");
                    break;
                case 2: // sebagai kasir
                    header("Location: " . $base_url . "kasir/daftar_pesanan.php");
                    break;
                case 3: // sebagai pelanggan
                    header("Location: " . $base_url . "pelanggan/beranda.php");
                    break;
                case 4: // sebagai Admin
                    header("Location: " . $base_url . "admin/dashboard.php");
                    break;
                default: // jika tidak ada role maka menampilkan halaman login
                    header("Location: " . $base_url . "auth/login.php");
                    break;
            }
            exit(); 
            // menampilkan keterangan jika gagal masuk
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!-- tampilan menu login -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Jus Mania</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: #e0f2f1;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #00796b;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #004d40;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
        .link-btn {
            width: 100%;
            padding: 10px;
            background: #0288d1;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        .link-btn:hover {
            background: #0277bd;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login Jus Mania</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <!-- Tombol Login -->
        <button type="submit" name="login">Login</button>

        <!-- Tombol Daftar -->
        <button type="button" class="link-btn" onclick="window.location.href='register.php'">Daftar</button>
    </form>
</body>
</html>
