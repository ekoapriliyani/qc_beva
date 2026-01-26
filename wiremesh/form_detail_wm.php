<?php 
include("../koneksi.php"); 

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include_once 'functions.php';

// Ambil data user dari session untuk info di dashboard
$userName = $_SESSION["name"];
$userRole = $_SESSION["role"];

if (isset($_GET['id'])) {
    $id_inspeksi = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query JOIN untuk mengambil data Inspeksi dan Detail Produk (termasuk prod_code)
    $sql = "SELECT h.*, p.prod_code, p.jarak_mesh, p.d_kawat, p.tol_min, p.tol_plus, 
                   p.p_produk, p.l_produk, p.p_mesh, p.l_mesh 
            FROM t_inspeksi_wm h
            JOIN t_produk p ON h.prod_code = p.prod_code
            WHERE h.id_inspeksi = '$id_inspeksi'";
            
    $result = mysqli_query($conn, $sql);
    $header = mysqli_fetch_assoc($result);

    if (!$header) {
        echo "<script>alert('Data Inspeksi atau Produk tidak ditemukan!'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Detail Inspeksi - QC System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .readonly-field { background-color: #f0f0f0; cursor: not-allowed; font-weight: 600; color: #555; }
        .section-title { grid-column: span 2; margin-top: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px; color: var(--primary-color); font-size: 14px; text-transform: uppercase; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-microscope"></i> Tambah Detail Pengukuran</h2>
        <p>ID Inspeksi: <strong><?php echo $id_inspeksi; ?></strong></p>
    </div>

    <form action="proses_simpan_detail_wm.php" method="POST">
        <input type="hidden" name="id_inspeksi" value="<?php echo $id_inspeksi; ?>">
        <div class="grid-container">
            <div class="section-title"><i class="fas fa-info-circle"></i> Standar Referensi (Tabel Produk)</div>
            
            <div class="form-group">
                <label for="">PRO Number</label>
                <input type="text" class="readonly-field" value="<?=  $header['pro_number']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Product Code</label>
                <input type="text" class="readonly-field" value="<?php echo $header['prod_code']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>D. Kawat Standard (Tol: <?php echo $header['tol_min']; ?> / <?php echo $header['tol_plus']; ?>)</label>
                <input type="text" class="readonly-field" value="<?php echo $header['d_kawat'] . ' mm'; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Jarak Mesh Standar</label>
                <input type="text" class="readonly-field" value="<?php echo $header['jarak_mesh'] . ' mm'; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>P x L Produk Standar</label>
                <input type="text" class="readonly-field" value="<?php echo $header['p_produk'] . ' x ' . $header['l_produk'] . ' mm'; ?>" readonly>
            </div>
            <div class="form-group">
                <label>P x L Mesh Standar</label>
                <input type="text" class="readonly-field" value="<?php echo $header['p_mesh'] . ' x ' . $header['l_mesh'] . ' mm'; ?>" readonly>
            </div>

            <div class="form-group full-width">
                <label for="">Material</label>
                <input type="text" name="material" autofocus>
            </div>
            
            <div class="form-group full-width">
                <label for="">Nama Operator Produksi</label>
                <input type="text" name="operator_prod">
            </div>

            <div class="section-title"><i class="fas fa-edit"></i> Hasil Pengukuran Aktual</div>

            <div class="form-group full-width">
                <label>Diameter Kawat Act (mm)</label>
                <input type="number" step="0.01" name="d_kawat_act" required>
            </div>
            <div class="form-group">
                <label>Panjang Produk Act (mm)</label>
                <input type="number" name="p_produk_act" required>
            </div>
            <div class="form-group">
                <label>Lebar Produk Act (mm)</label>
                <input type="number" name="l_produk_act" required>
            </div>
            <div class="form-group">
                <label>Panjang Mesh Act (mm)</label>
                <input type="number" name="p_mesh_act" required>
            </div>
            <div class="form-group">
                <label>Lebar Mesh Act (mm)</label>
                <input type="number" name="l_mesh_act" required>
            </div>
            <div class="form-group">
                <label>Selisih Diagonal (mm)</label>
                <input type="number" name="diagonal" required>
            </div>
            <div class="form-group">
                <label>Shear Strength (MPa)</label>
                <input type="number" step="0.1" name="shear_strght_mpa" required>
            </div>
            <div class="form-group">
                <label>Torsi Strength</label>
                <select name="torsi_strgh" required>
                    <option value="OK">OK</option>
                    <option value="NG">NG</option>
                </select>
            </div>
            <div class="form-group">
                <label>Visual Check</label>
                <select name="visual" required>
                    <option value="OK">OK</option>
                    <option value="NG">NG</option>
                </select>
            </div>
            <!-- <div class="form-group full-width">
                <label>Visual Check</label>
                <select name="visual" required>
                    <option value="0">OK</option>
                    <option value="1">Crack</option>
                    <option value="2">Karat</option>
                    <option value="3">Las (Lepas/Tidak ngelas)</option>
                    <option value="4">CW-LW (Pendek/Bengkok/Putus)</option>
                    <option value="5">Triming</option>
                    <option value="6">Mesh</option>
                    <option value="7">Handling</option>
                </select>
            </div> -->
        </div>

        <div class="btn-container">
            <a href="preview.php?id=<?php echo $id_inspeksi; ?>" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-submit">Simpan Detail</button>
        </div>
    </form>
</div>

</body>
</html>