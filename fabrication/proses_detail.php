<?php
include "koneksi.php";

// Ambil Data POST
$id_fabrication   = $_POST['id_fabrication'];

var_dump($id_fabrication);
$progress_ke  = $_POST['progress_ke'];
$part_desc    = mysqli_real_escape_string($conn, $_POST['part_desc']);
$size    = mysqli_real_escape_string($conn, $_POST['size']);
$dis_hole    = mysqli_real_escape_string($conn, $_POST['dis_hole']);
$angle    = mysqli_real_escape_string($conn, $_POST['angle']);
$straighness    = mysqli_real_escape_string($conn, $_POST['straighness']);
$welding    = mysqli_real_escape_string($conn, $_POST['welding']);
$qty    = mysqli_real_escape_string($conn, $_POST['qty']);

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
    // A. Simpan ke tabel Utama (t_fabrication_detail)
    $query_detail = "INSERT INTO t_fabrication_detail 
                     (id_fabrication, progress_ke, part_desc, size, dis_hole, angle, straighness, welding, qty, result, inspector, foto) 
                     VALUES 
                     ('$id_fabrication', '$progress_ke', '$part_desc', '$size', '$dis_hole', '$angle', '$straighness', '$welding', '$qty', '$final_result', '$inspector', '$foto_db')";
    
    if (!mysqli_query($conn, $query_detail)) {
        throw new Exception(mysqli_error($conn));
    }

    // Ambil ID Detail yang baru saja masuk
    $id_detail_baru = mysqli_insert_id($conn);

    // B. Simpan ke tabel NG (t_fabrication_ng) jika ada input NG
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
    header("Location: tambah_detail.php?id_fabrication=$id_fabrication&status=success");

} catch (Exception $e) {
    // Jika ada yang gagal, Batalkan semua (Rollback)
    mysqli_rollback($conn);
    echo "Gagal menyimpan data: " . $e->getMessage();
}
?>