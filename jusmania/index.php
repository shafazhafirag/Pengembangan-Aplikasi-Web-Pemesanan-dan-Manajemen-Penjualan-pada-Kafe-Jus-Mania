<?php

session_start(); // memulai sesi

// Jika sudah login, arahkan berdasarkan role
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 1: // dapur
            header("Location: dapur/proses_pesanan.php");
            exit();
        case 2: // kasir
            header("Location: kasir/daftar_pesanan.php");
            exit();
        case 3: // pelanggan
            header("Location: pelanggan/beranda.php");
            exit();
        case 4: // admin
            header("Location: admin/dashboard.php");
            exit();            
        default:
            session_start();
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();

    }
}
?>

<!-- tampilan web jika belum pernah login-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jus Mania - Selamat Datang</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7fa;
            text-align: center;
            padding-top: 80px;
        }
        h1 {
            font-size: 2.5rem;
            color: #00796b;
        }
        .container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #00796b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Jus Mania 🍹</h1>
        <p>Sistem Pemesanan & Manajemen Penjualan Jus Segar</p>
        <a href="auth/login.php" class="btn">Masuk</a>
    </div>
</body>
</html>
