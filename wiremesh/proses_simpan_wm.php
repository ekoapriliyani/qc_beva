<?php
include("../koneksi.php");

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include_once 'functions.php';

// Ambil data user dari session untuk info di dashboard
$userName = $_SESSION["name"];
$userRole = $_SESSION["role"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form dan memproteksi dari SQL Injection sederhana
    $hari_tgl            = mysqli_real_escape_string($conn, $_POST['hari_tgl']);
    $shift            = mysqli_real_escape_string($conn, $_POST['shift']);
    $pro_number                 = mysqli_real_escape_string($conn, $_POST['pro_number']);
    $mesin               = mysqli_real_escape_string($conn, $_POST['mesin']);
    $merk                = mysqli_real_escape_string($conn, $_POST['merk']);
    $prod_code           = mysqli_real_escape_string($conn, $_POST['prod_code']);
    $type_coating        = mysqli_real_escape_string($conn, $_POST['type_coating']);
    $status              = mysqli_real_escape_string($conn, $_POST['status']);
    $jml_sample_diambil  = mysqli_real_escape_string($conn, $_POST['jml_sample_diambil']);
    $jml_ng              = mysqli_real_escape_string($conn, $_POST['jml_ng']);
    $status_repair       = mysqli_real_escape_string($conn, $_POST['status_repair']);

    // Query INSERT
    $query = "INSERT INTO t_inspeksi_wm (
                hari_tgl, 
                shift,
                pro_number, 
                mesin,
                merk, 
                prod_code, 
                type_coating, 
                status, 
                jml_sample_diambil, 
                jml_ng, 
                status_repair
              ) VALUES (
                '$hari_tgl', 
                '$shift', 
                '$pro_number', 
                '$mesin', 
                '$merk', 
                '$prod_code', 
                '$type_coating', 
                '$status', 
                '$jml_sample_diambil', 
                '$jml_ng', 
                '$status_repair'
              )";

    // Eksekusi Query
    if (mysqli_query($conn, $query)) {
        // Jika berhasil, alihkan ke halaman index dengan pesan sukses
        echo "<script>
                alert('Data Inspeksi Berhasil Disimpan!');
                window.location.href='index.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    // Tutup koneksi
    mysqli_close($conn);
} else {
    // Jika mencoba akses langsung tanpa POST, tendang balik ke form
    header("Location: form_inspeksi_wiremesh.php");
}
?>