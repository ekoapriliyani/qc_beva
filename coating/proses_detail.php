<?php
include "koneksi.php";

$id_coating   = $_POST['id_coating'];
$part_desc    = mysqli_real_escape_string($conn, $_POST['part_desc']);
$t_1          = !empty($_POST['t_1']) ? $_POST['t_1'] : 0;
$t_2          = !empty($_POST['t_2']) ? $_POST['t_2'] : 0;
$t_3          = !empty($_POST['t_3']) ? $_POST['t_3'] : 0;
$t_4          = !empty($_POST['t_4']) ? $_POST['t_4'] : 0;
$t_5          = !empty($_POST['t_5']) ? $_POST['t_5'] : 0;
$avg          = $_POST['avg'];
$qty          = !empty($_POST['qty']) ? $_POST['qty'] : 1;
$result_input = $_POST['result']; 
$inspector    = $_POST['inspector'];

// Logika Visual Check
if (isset($_POST['visual_check'])) {
    $visual_check = "ACC";
    $final_result = $result_input; 
} else {
    $visual_check = "NG";
    $final_result = "NG"; 
}

// --- LOGIKA UPLOAD MULTIPLE FILE ---
$nama_file_random = []; // Array untuk menampung nama file baru
$target_dir = "uploads/";

// Buat folder uploads jika belum ada
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Cek apakah ada file yang diupload (menggunakan name="attachments[]" dari form sebelumnya)
if (!empty($_FILES['attachments']['name'][0])) {
    foreach ($_FILES['attachments']['name'] as $key => $val) {
        $file_tmp  = $_FILES['attachments']['tmp_name'][$key];
        $file_name = $_FILES['attachments']['name'][$key];
        $file_ext  = pathinfo($file_name, PATHINFO_EXTENSION);
        
        // Buat nama random unik: contoh 65c123abc456.jpg
        $new_file_name = uniqid() . "." . $file_ext;
        $target_file   = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $nama_file_random[] = $new_file_name;
        }
    }
}

// Gabungkan nama-nama file menjadi satu string dipisahkan koma
$foto_db = implode(",", $nama_file_random);

// Query Insert (Ditambah kolom foto)
$query = "INSERT INTO t_coating_detail 
          (id_coating, part_desc, t_1, t_2, t_3, t_4, t_5, avg, visual_check, qty, result, inspector, foto) 
          VALUES 
          ('$id_coating', '$part_desc', '$t_1', '$t_2', '$t_3', '$t_4', '$t_5', '$avg', '$visual_check', '$qty', '$final_result', '$inspector', '$foto_db')";

if (mysqli_query($conn, $query)) {
    header("Location: tambah_detail.php?id_coating=$id_coating&status=success");
} else {
    echo "Error SQL: " . mysqli_error($conn);
}
?>