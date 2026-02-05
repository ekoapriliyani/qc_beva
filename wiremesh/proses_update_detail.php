<?php
include("../koneksi.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_detail        = mysqli_real_escape_string($conn, $_POST['id_detail']);
    $id_main          = mysqli_real_escape_string($conn, $_POST['id_main']);
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

    // --- Update data utama inspeksi detail ---
    $query = "UPDATE t_inspeksi_wm_detail SET 
                material='$material',
                operator_prod='$operator_prod',
                d_kawat_act='$d_kawat_act',
                p_produk_act='$p_produk_act',
                l_produk_act='$l_produk_act',
                p_mesh_act='$p_mesh_act',
                l_mesh_act='$l_mesh_act',
                diagonal='$diagonal',
                torsi_strgh='$torsi_strgh',
                status_dimensi='$status_dimensi'
              WHERE id_detail='$id_detail'";

    if (mysqli_query($conn, $query)) {
        // --- Update Visual Detail jika ada ---
        if (!empty($_POST['visual_detail']) || !empty($_POST['keterangan'])) {
            $visual_detail = mysqli_real_escape_string($conn, $_POST['visual_detail']);
            $keterangan    = mysqli_real_escape_string($conn, $_POST['keterangan']);

            // cek apakah sudah ada id_visual_detail
            $cek = mysqli_query($conn, "SELECT id_visual_detail FROM t_inspeksi_wm_detail WHERE id_detail='$id_detail'");
            $row = mysqli_fetch_assoc($cek);

            if (!empty($row['id_visual_detail'])) {
                // update visual detail yang sudah ada
                $sql_visual = "UPDATE t_visual_detail 
                               SET visual_detail='$visual_detail', keterangan='$keterangan' 
                               WHERE id=".$row['id_visual_detail'];
                mysqli_query($conn, $sql_visual);
            } else {
                // buat baru jika belum ada
                $sql_visual = "INSERT INTO t_visual_detail (visual_detail, keterangan) 
                               VALUES ('$visual_detail', '$keterangan')";
                if (mysqli_query($conn, $sql_visual)) {
                    $new_id_visual = mysqli_insert_id($conn);
                    mysqli_query($conn, "UPDATE t_inspeksi_wm_detail 
                                         SET id_visual_detail='$new_id_visual' 
                                         WHERE id_detail='$id_detail'");
                }
            }
        }

        echo "<script>
                alert('Data berhasil diupdate!');
                window.location.href='preview.php?id=$id_main';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>