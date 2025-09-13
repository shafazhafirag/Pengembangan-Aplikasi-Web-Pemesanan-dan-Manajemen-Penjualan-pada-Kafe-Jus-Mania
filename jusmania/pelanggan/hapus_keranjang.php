<?php
include '../config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_keranjang'])) {
    $id_keranjang = intval($_POST['id_keranjang']);
    $id_user = $_SESSION['id'] ?? null;

    if ($id_user) {
        $query = "DELETE FROM keranjang WHERE id = $id_keranjang AND id_user = $id_user";
        mysqli_query($conn, $query);
    }
}

header("Location: keranjang.php");
exit();
