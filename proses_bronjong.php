<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'tanggal'     => $_POST['tanggal'],
        'shift'       => $_POST['shift'],
        'waktu'       => date('H:i:s'),
        'operator'    => $_POST['operator'],
        'no_pro'      => $_POST['no_pro'],
        'ukuran'      => $_POST['ukuran'] ?? null,
        'lebar_1'     => $_POST['lebar_1'] ?: 0,
        'lebar_2'     => $_POST['lebar_2'] ?: 0,
        'jenis'       => $_POST['jenis'],
        'kelas'       => $_POST['kelas'],
        'tipe'        => $_POST['tipe'],
        'd_anyam'     => $_POST['d_anyam'] ?: 0,
        'd_frame'     => $_POST['d_frame'] ?: 0,
        'd_anyam_2'   => $_POST['d_anyam_2'] ?: 0,
        'd_frame_2'   => $_POST['d_frame_2'] ?: 0,
        'p_lilitan'   => $_POST['p_lilitan'] ?: 0,
        'jml_lilitan' => $_POST['jml_lilitan'] ?: 0,
        'status'      => $_POST['status']
    ];

    if (simpan_bronjong($data)) {
        // Berhasil: Lempar status=success
        header("Location: form_inspeksi_bronjong.php?status=success");
        exit();
    } else {
        // Gagal: Lempar status=error
        header("Location: form_inspeksi_bronjong.php?status=error");
        exit();
    }
}