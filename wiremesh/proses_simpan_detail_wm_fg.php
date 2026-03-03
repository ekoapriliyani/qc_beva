<?php
include("../koneksi.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data utama (Single)
    $id_inspeksi   = mysqli_real_escape_string($conn, $_POST['id_inspeksi']);
    $batch_number  = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);
    $qty           = mysqli_real_escape_string($conn, $_POST['qty']);

    // 2. Ambil data multiple (Array)
    $visual_details = $_POST['visual_detail']; // Ambil array visual
    $visual_kets    = $_POST['visual_ket'];    // Ambil array keterangan

    // 3. Gabungkan array menjadi satu string agar hanya tersimpan 1 baris
    // Hasilnya akan seperti: "Triming (ket: tajam), Mesh (ket: renggang)"
    $combined_visual = [];
    foreach ($visual_details as $index => $detail) {
        $ket = !empty($visual_kets[$index]) ? " (" . $visual_kets[$index] . ")" : "";
        $combined_visual[] = $detail . $ket;
    }
    
    // Gabung dengan pemisah koma
    $visual_string = mysqli_real_escape_string($conn, implode(", ", $combined_visual));

    // 4. Query INSERT (Hanya satu kali jalan per submit)
    $query = "INSERT INTO t_inspeksi_wm_fg (
                id_inspeksi, 
                visual_detail, 
                batch_number, 
                status, 
                qty, 
                created_at
              ) VALUES (
                '$id_inspeksi', 
                '$visual_string', 
                '$batch_number', 
                '$status', 
                '$qty', 
                NOW()
              )";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data Finish Good berhasil disimpan dalam 1 ID!');
                window.location.href='preview.php?id=$id_inspeksi';
              </script>";
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>