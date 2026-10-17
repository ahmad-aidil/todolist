<?php
// konfigurasi koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_todolist";

//Membuat koneski
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Penanganan error konesksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>