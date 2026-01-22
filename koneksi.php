<?php
$host = "localhost";
$user = "root";
$pass = "Eko123$";
$db   = "qc_inspection"; // Ganti dengan nama database Anda

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>