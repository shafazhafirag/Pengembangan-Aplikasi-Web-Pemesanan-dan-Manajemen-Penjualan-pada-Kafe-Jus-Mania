<?php
session_start();
include '../config/db.php';

$id_user = $_SESSION['id'] ?? 0;

if (!$id_user) {
    echo "Anda belum login.";
    exit();
}

// Ambil data dari keranjang
$query = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user = $id_user");

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $id_menu = $row['id_menu'];
        $jumlah = $row['jumlah'];
        $pakai_es = $row['pakai_es'];
        $pakai_gula = $row['pakai_gula'];
        $id_update_ad = 1; // unpaid

        // Masukkan ke tabel pesanan, dengan menyertakan es dan gula
        mysqli_query($conn, "INSERT INTO pesanan (id_user, id_menu, jumlah, id_update_ad, pakai_es, pakai_gula)
                             VALUES ($id_user, $id_menu, $jumlah, $id_update_ad, $pakai_es, $pakai_gula)");
    }

    // Hapus isi keranjang setelah pesanan diproses
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = $id_user");

    // Redirect ke invoice
    header("Location: invoice.php");
    exit();
} else { // jika keranjang kosong
    echo "Keranjang kosong.";
}
?>
