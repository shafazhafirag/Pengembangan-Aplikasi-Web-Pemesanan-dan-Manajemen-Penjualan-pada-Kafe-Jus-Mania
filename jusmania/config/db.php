<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "jusmania";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil!";

$base_url = "/jusmania/";
?>
