<?php

require '../koneksi.php';

// fungsi query tampil data
function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah_pro($data){
    global $conn;
    
    $pro_number = $data["pro_number"];
    $qty_prod = $data['qty_prod'];
    $query = "INSERT INTO t_pro (pro_number, qty_prod) VALUES('$pro_number', '$qty_prod')";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function update_wm($post) {
    global $conn;

    $id             = mysqli_real_escape_string($conn, $post['id']);
    $jml_ng         = mysqli_real_escape_string($conn, $post['jml_ng']);
    $jml_reject     = mysqli_real_escape_string($conn, $post['jml_reject']);
    $total_produksi = mysqli_real_escape_string($conn, $post['total_produksi']);
    $status_repair  = mysqli_real_escape_string($conn, $post['status_repair']);

    $query = "UPDATE t_inspeksi_wm 
              SET jml_ng = '$jml_ng',
                  jml_reject = '$jml_reject',
                  total_produksi = '$total_produksi',
                  status_repair = '$status_repair'
              WHERE id_inspeksi = '$id'";

    return mysqli_query($conn, $query);
}




// fungsi tambah data
// function tambah($data)
// {
//     global $conn;
//     $nama_platform = $data["nama_platform"];
//     $versi = $data["versi"];
//     $deskripsi = $data["deskripsi"];

//     $query = "INSERT INTO platform VALUES(
//         '', '$nama_platform', '$versi', '$deskripsi'
//     )";

//     mysqli_query($conn, $query);
//     return mysqli_affected_rows($conn);
// }


// fungsi ubah data
function ubah($data)
{
    global $conn;
    $id = $data["id"];
    $nama_platform = $data["nama_platform"];
    $versi = $data["versi"];
    $deskripsi = $data["deskripsi"];

    $query = "UPDATE platform SET
        nama_platform = '$nama_platform',
        versi = '$versi',
        deskripsi = '$deskripsi'
        WHERE id = $id
    ";

    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}


// fungsi hapus
function hapus($id)
{
    global $conn;
    mysqli_query($conn, "DELETE FROM platform WHERE id = $id");
    return mysqli_affected_rows($conn);
}