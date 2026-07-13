<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "petadopt_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>