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
    $id_inspeksi        = $_POST['id_inspeksi'];
    $material           = $_POST['material'];
    $operator_prod           = $_POST['operator_prod'];
    $material           = $_POST['material'];
    $d_kawat_act        = $_POST['d_kawat_act'];
    $p_produk_act       = $_POST['p_produk_act'];
    $l_produk_act       = $_POST['l_produk_act'];
    $p_mesh_act         = $_POST['p_mesh_act'];
    $l_mesh_act         = $_POST['l_mesh_act'];
    $diagonal           = $_POST['diagonal'];
    $shear_strght_mpa   = $_POST['shear_strght_mpa'];
    $torsi_strgh        = $_POST['torsi_strgh'];
    $visual             = $_POST['visual'];

    $query = "INSERT INTO t_inspeksi_wm_detail (
                id_inspeksi, material, operator_prod, d_kawat_act, p_produk_act, l_produk_act, 
                p_mesh_act, l_mesh_act, diagonal, shear_strght_mpa, 
                torsi_strgh, visual
              ) VALUES (
                '$id_inspeksi', '$material', '$operator_prod', '$d_kawat_act', '$p_produk_act', '$l_produk_act', 
                '$p_mesh_act', '$l_mesh_act', '$diagonal', '$shear_strght_mpa', 
                '$torsi_strgh', '$visual'
              )";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Detail Pengukuran Berhasil Ditambahkan!');
                window.location.href='preview.php?id=$id_inspeksi';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>