<?php
include("../koneksi.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_inspeksi   = mysqli_real_escape_string($conn, $_POST['id_inspeksi']);
    $visual_detail = mysqli_real_escape_string($conn, $_POST['visual_detail']);
    $batch_number  = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);
    $qty           = mysqli_real_escape_string($conn, $_POST['qty']);

    $query = "INSERT INTO t_inspeksi_wm_fg (
                id_inspeksi, visual_detail, batch_number, status, qty, created_at
              ) VALUES (
                '$id_inspeksi', '$visual_detail', '$batch_number', '$status', '$qty', NOW()
              )";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Finish Good berhasil disimpan!');
                window.location.href='preview.php?id=$id_inspeksi';
              </script>";
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>