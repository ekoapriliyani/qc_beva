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
$result_input = $_POST['result']; // Menangkap ACC atau NG
$inspector    = $_POST['inspector'];

// Logika Visual Check & Result Otomatis
if (isset($_POST['visual_check'])) {
    $visual_check = "ACC";
    $final_result = $result_input; // Tergantung pilihan dropdown
} else {
    $visual_check = "NG";
    $final_result = "NG"; // Paksa NG jika visual tidak diceklis
}

$query = "INSERT INTO t_coating_detail 
          (id_coating, part_desc, t_1, t_2, t_3, t_4, t_5, avg, visual_check, qty, result, inspector) 
          VALUES 
          ('$id_coating', '$part_desc', '$t_1', '$t_2', '$t_3', '$t_4', '$t_5', '$avg', '$visual_check', '$qty', '$final_result', '$inspector')";

if (mysqli_query($conn, $query)) {
    header("Location: tambah_detail.php?id_coating=$id_coating");
} else {
    echo "Error SQL: " . mysqli_error($conn);
}
?>