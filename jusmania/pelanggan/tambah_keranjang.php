<?php
include '../config/db.php';
include '../includes/header.php';
session_start();
// meriksa adakah iduser pada session
if (!isset($_SESSION['id'])) {
    echo "Anda belum login.";
    exit();
}
// mengambil data id user, menambahkan data ke database menu, jumlah dan custom gula, es
$id_user = $_SESSION['id'];
$id_menu = intval($_POST['id_menu']);
$jumlah = intval($_POST['jumlah']);
$pakai_es = isset($_POST['pakai_es']) ? 1 : 0;
$pakai_gula = isset($_POST['pakai_gula']) ? 1 : 0;

// Cek apakah item dengan opsi sama sudah ada di keranjang
$cek = mysqli_query($conn, "SELECT * FROM keranjang 
    WHERE id_user = $id_user 
    AND id_menu = $id_menu 
    AND pakai_es = $pakai_es 
    AND pakai_gula = $pakai_gula");

if (mysqli_num_rows($cek) > 0) {
    // Item sudah ada di keranjang
    echo "<script>alert('Item dengan opsi yang sama sudah ada di keranjang.'); window.location.href='keranjang.php';</script>";
    exit();
}

// Insert item baru ke keranjang
$query = "INSERT INTO keranjang (id_user, id_menu, jumlah, pakai_es, pakai_gula) 
          VALUES ($id_user, $id_menu, $jumlah, $pakai_es, $pakai_gula)";
// menampilkan halaman keranjang saat menekan tombol tambah keranjang
if (mysqli_query($conn, $query)) {
    header("Location: keranjang.php");
    exit();
} else { // jika dikeranjang sama datanya
    echo "Gagal menambahkan ke keranjang: " . mysqli_error($conn);
}
?>
