<?php
include("../koneksi.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_inspeksi   = mysqli_real_escape_string($conn, $_POST['id_inspeksi']);
    $batch_number  = mysqli_real_escape_string($conn, $_POST['batch_number']);
    $status        = mysqli_real_escape_string($conn, $_POST['status']);
    $qty           = mysqli_real_escape_string($conn, $_POST['qty']);
    $visual_details = $_POST['visual_detail'];
    $visual_kets    = $_POST['visual_ket'];

    // 1. Gabungkan Visual Detail menjadi 1 string
    $combined_visual = [];
    foreach ($visual_details as $index => $detail) {
        $ket = !empty($visual_kets[$index]) ? " (" . $visual_kets[$index] . ")" : "";
        $combined_visual[] = $detail . $ket;
    }
    $visual_string = mysqli_real_escape_string($conn, implode(", ", $combined_visual));

    // 2. Proses Upload Multiple Foto
    $uploaded_files = [];
    $target_dir = "uploads/fg/"; // Pastikan folder ini sudah ada dan writable

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES['foto_fg']['name'][0])) {
        foreach ($_FILES['foto_fg']['name'] as $key => $val) {
            $file_name = $_FILES['foto_fg']['name'][$key];
            $file_tmp  = $_FILES['foto_fg']['tmp_name'][$key];
            
            // Buat nama file unik: ID_BATCH_WAKTU_NAMAFILE
            $new_file_name = $id_inspeksi . "_" . $batch_number . "_" . time() . "_" . $file_name;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $uploaded_files[] = $new_file_name;
            }
        }
    }
    
    // Gabung nama file foto menjadi string dipisahkan koma
    $foto_string = mysqli_real_escape_string($conn, implode(",", $uploaded_files));

    // 3. Simpan ke Database (1 ID)
    $query = "INSERT INTO t_inspeksi_wm_fg (
                id_inspeksi, 
                visual_detail, 
                batch_number, 
                status, 
                qty, 
                foto,
                created_at
              ) VALUES (
                '$id_inspeksi', 
                '$visual_string', 
                '$batch_number', 
                '$status', 
                '$qty', 
                '$foto_string',
                NOW()
              )";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data & Foto Finish Good berhasil disimpan!');
                window.location.href='preview.php?id=$id_inspeksi';
              </script>";
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>