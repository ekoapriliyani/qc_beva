<?php
include("../koneksi.php");
$id_detail = $_GET['id_detail'];
$id_main = $_GET['id_main'];

$query = "DELETE FROM t_inspeksi_wm_detail WHERE id_detail = '$id_detail'";
if (mysqli_query($conn, $query)) {
    header("Location: preview.php?id=$id_main");
} else {
    echo "Gagal menghapus: " . mysqli_error($conn);
}
?>