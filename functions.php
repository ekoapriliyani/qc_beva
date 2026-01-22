<?php
require_once 'config.php';

/**
 * GLOBAL FUNCTIONS
 */

// Untuk navigasi menu utama
function get_list_produk() {
    global $pdo;
    // Mengambil daftar menu untuk dashboard
    return $pdo->query("SELECT * FROM m_produk ORDER BY id_produk ASC")->fetchAll();
}

/**
 * BRONJONG FUNCTIONS
 */

function get_all_inspeksi_bronjong() {
    global $pdo;
    // Menampilkan data terbaru di atas (DESC)
    $stmt = $pdo->query("SELECT * FROM t_inspeksi_bronjong ORDER BY id_bronjong DESC");
    return $stmt->fetchAll();
}

function simpan_bronjong($data) {
    global $pdo;
    try {
        $sql = "INSERT INTO t_inspeksi_bronjong (
                    tanggal, shift, waktu, operator, no_pro, 
                    ukuran, lebar_1, lebar_2, jenis, kelas, 
                    tipe, d_anyam, d_frame, d_anyam_2, d_frame_2, 
                    p_lilitan, jml_lilitan, status
                ) VALUES (
                    :tanggal, :shift, :waktu, :operator, :no_pro, 
                    :ukuran, :lebar_1, :lebar_2, :jenis, :kelas, 
                    :tipe, :d_anyam, :d_frame, :d_anyam_2, :d_frame_2, 
                    :p_lilitan, :jml_lilitan, :status
                )";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    } catch (PDOException $e) {
        // Log error atau tampilkan untuk debugging IT Support
        error_log("Error Simpan Bronjong: " . $e->getMessage());
        return false;
    }
}

/**
 * WIREMESH FUNCTIONS (Persiapan Produk Berikutnya)
 */
function simpan_wiremesh($data) {
    global $pdo;
    // Nanti tinggal buat tabel t_inspeksi_wiremesh dan sesuaikan fieldnya di sini
}

/**
 * READ FUNCTIONS (Untuk Laporan/History)
 */
function get_history_bronjong($limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM t_inspeksi_bronjong ORDER BY id_bronjong DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
?>