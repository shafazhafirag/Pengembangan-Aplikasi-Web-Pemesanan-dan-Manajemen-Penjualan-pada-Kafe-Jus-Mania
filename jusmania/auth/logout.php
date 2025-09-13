<?php
// menghendel session, menghentikan proses session kemudian menampilkan halaman login
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit();
