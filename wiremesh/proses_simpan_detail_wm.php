<?php
include("../koneksi.php");

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// include_once 'functions.php';

// Ambil data user dari session untuk info di dashboard
$userName = $_SESSION["name"];
$userRole = $_SESSION["role"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_inspeksi      = mysqli_real_escape_string($conn, $_POST['id_inspeksi']);
    $material         = mysqli_real_escape_string($conn, $_POST['material']);
    $operator_prod    = mysqli_real_escape_string($conn, $_POST['operator_prod']);
    $d_kawat_act      = mysqli_real_escape_string($conn, $_POST['d_kawat_act']);
    $p_produk_act     = mysqli_real_escape_string($conn, $_POST['p_produk_act']);
    $l_produk_act     = mysqli_real_escape_string($conn, $_POST['l_produk_act']);
    $p_mesh_act       = mysqli_real_escape_string($conn, $_POST['p_mesh_act']);
    $l_mesh_act       = mysqli_real_escape_string($conn, $_POST['l_mesh_act']);
    $diagonal         = mysqli_real_escape_string($conn, $_POST['diagonal']);
    $torsi_strgh      = mysqli_real_escape_string($conn, $_POST['torsi_strgh']);
    $status_dimensi   = mysqli_real_escape_string($conn, $_POST['status_dimensi']);

    // --- Simpan Visual Detail jika ada ---
    $id_visual_detail = "NULL"; // default jika tidak ada
    if (!empty($_POST['visual_detail']) || !empty($_POST['keterangan'])) {
        $visual_detail = mysqli_real_escape_string($conn, $_POST['visual_detail']);
        $keterangan    = mysqli_real_escape_string($conn, $_POST['keterangan']);

        $sql_visual = "INSERT INTO t_visual_detail (visual_detail, keterangan) 
                       VALUES ('$visual_detail', '$keterangan')";
        if (mysqli_query($conn, $sql_visual)) {
            $id_visual_detail = mysqli_insert_id($conn);
        }
    }

    // --- Simpan Detail Inspeksi ---
    $query = "INSERT INTO t_inspeksi_wm_detail (
                id_inspeksi, material, operator_prod, d_kawat_act, p_produk_act, l_produk_act, 
                p_mesh_act, l_mesh_act, diagonal,  
                torsi_strgh, status_dimensi, id_visual_detail
              ) VALUES (
                '$id_inspeksi', '$material', '$operator_prod', '$d_kawat_act', '$p_produk_act', '$l_produk_act', 
                '$p_mesh_act', '$l_mesh_act', '$diagonal',
                '$torsi_strgh', '$status_dimensi', $id_visual_detail
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