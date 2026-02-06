<?php
include "koneksi.php";
$pro_number = $_POST['pro_number'];
$qty = $_POST['qty'];
$id_project = $_POST['id_project'];
$id_subkon  = $_POST['id_subkon'];
$tgl        = $_POST['tgl'];
$report_no  = $_POST['report_no'];
$drawing_no = $_POST['drawing_no'];

$query = "INSERT INTO t_coating_header (pro_number, qty, id_project, id_subkon, tgl, report_no, drawing_no) 
          VALUES ('$pro_number', '$qty', '$id_project', '$id_subkon', '$tgl', '$report_no', '$drawing_no')";

if (mysqli_query($conn, $query)) {
    $last_id = mysqli_insert_id($conn);
    // Lempar ke halaman input detail sambil membawa ID Header
    header("Location: tambah_detail.php?id_coating=$last_id");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>