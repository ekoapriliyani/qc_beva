<?php
include "koneksi.php";

// Ambil Data POST
$id_coating   = $_POST['id_coating'];
$progress_ke  = $_POST['progress_ke'];
$part_desc    = mysqli_real_escape_string($conn, $_POST['part_desc']);
$t_1          = !empty($_POST['t_1']) ? $_POST['t_1'] : 0;
$t_2          = !empty($_POST['t_2']) ? $_POST['t_2'] : 0;
$t_3          = !empty($_POST['t_3']) ? $_POST['t_3'] : 0;
$t_4          = !empty($_POST['t_4']) ? $_POST['t_4'] : 0;
$t_5          = !empty($_POST['t_5']) ? $_POST['t_5'] : 0;
$avg          = $_POST['avg'];
$qty          = !empty($_POST['qty']) ? $_POST['qty'] : 1;
$result_input = $_POST['result']; 
$inspector    = mysqli_real_escape_string($conn, $_POST['inspector']);

// Logika Visual Check & Final Result
if (isset($_POST['visual_check'])) {
    $visual_check = "ACC";
    $final_result = $result_input; 
} else {
    $visual_check = "NG";
    $final_result = "NG"; 
}

// --- 1. PROSES UPLOAD FOTO ---
$nama_file_random = [];
$target_dir = "uploads/";
if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

if (!empty($_FILES['attachments']['name'][0])) {
    foreach ($_FILES['attachments']['name'] as $key => $val) {
        $file_tmp  = $_FILES['attachments']['tmp_name'][$key];
        $file_name = $_FILES['attachments']['name'][$key];
        $file_ext  = pathinfo($file_name, PATHINFO_EXTENSION);
        
        $new_file_name = uniqid('IMG_') . "." . $file_ext;
        $target_file   = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $nama_file_random[] = $new_file_name;
        }
    }
}
$foto_db = implode(",", $nama_file_random);

// --- 2. MULAI DATABASE TRANSACTION ---
mysqli_begin_transaction($conn);

try {
    // A. Simpan ke tabel Utama (t_coating_detail)
    $query_detail = "INSERT INTO t_coating_detail 
                     (id_coating, progress_ke, part_desc, t_1, t_2, t_3, t_4, t_5, avg, visual_check, qty, result, inspector, foto) 
                     VALUES 
                     ('$id_coating', '$progress_ke', '$part_desc', '$t_1', '$t_2', '$t_3', '$t_4', '$t_5', '$avg', '$visual_check', '$qty', '$final_result', '$inspector', '$foto_db')";
    
    if (!mysqli_query($conn, $query_detail)) {
        throw new Exception(mysqli_error($conn));
    }

    // Ambil ID Detail yang baru saja masuk
    $id_detail_baru = mysqli_insert_id($conn);

    // B. Simpan ke tabel NG (t_coating_ng) jika ada input NG
    if (isset($_POST['ng_type']) && is_array($_POST['ng_type'])) {
        foreach ($_POST['ng_type'] as $key => $type) {
            if (!empty($type)) {
                $type_clean   = mysqli_real_escape_string($conn, $type);
                $remark_clean = mysqli_real_escape_string($conn, $_POST['ng_remark'][$key]);
                
                $query_ng = "INSERT INTO t_coating_ng (id_detail, ng_type, ng_remark) 
                             VALUES ('$id_detail_baru', '$type_clean', '$remark_clean')";
                
                if (!mysqli_query($conn, $query_ng)) {
                    throw new Exception(mysqli_error($conn));
                }
            }
        }
    }

    // Jika semua OK, Commit!
    mysqli_commit($conn);
    header("Location: tambah_detail.php?id_coating=$id_coating&status=success");

} catch (Exception $e) {
    // Jika ada yang gagal, Batalkan semua (Rollback)
    mysqli_rollback($conn);
    echo "Gagal menyimpan data: " . $e->getMessage();
}
?>