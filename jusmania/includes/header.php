<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: /auth/login.php");
    exit();
}
?>
<!-- tampilan bagian atas pada seetiap halaman web-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jus Mania</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Gaya dasar header */
        header {
            background-color: #00796b;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
        }

        nav a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .user-info {
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <h1>Jus Mania</h1>
    <div class="user-info">
        Halo, <?= htmlspecialchars($_SESSION['nama']) ?> |
       <a href="/jusmania/auth/logout.php">Logout</a>


    </div>
</header>

