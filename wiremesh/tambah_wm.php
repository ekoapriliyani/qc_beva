<?php 
include("../koneksi.php"); 
require_once 'functions.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION["user_name"];
$userRole = $_SESSION["role"];

if (isset($_POST["save_pro"])) {
    if (tambah_pro($_POST)) {
        echo "<script>alert('PRO Number berhasil disimpan');</script>";
    } else {
        echo "<script>alert('PRO Number gagal disimpan');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Inspeksi Wiremesh - QC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 12px; }
        .form-label { font-weight: 600; font-size: 0.9rem; color: #444; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-0"><i class="fas fa-clipboard-check"></i> Form Inspeksi Wiremesh</h2>
            <p class="text-muted">Input hasil pengecekan kualitas produksi</p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Registrasi PRO Baru</h5>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">PRO Number</label>
                            <input type="text" name="pro_number" class="form-control" placeholder="Input PRO Baru" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">QTY Produksi</label>
                            <input type="number" name="qty_prod" class="form-control" placeholder="0">
                        </div>
                        <button type="submit" name="save_pro" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Daftarkan PRO
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Detail Data Inspeksi</h5>
                    <form action="proses_simpan_wm.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Inspector</label>
                                <input type="text" name="inspector" class="form-control bg-light" value="<?= $userName; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hari / Tanggal</label>
                                <input type="date" name="hari_tgl" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Shift</label>
                                <select name="shift" class="form-select" required>
                                    <option value="1">Shift 1</option>
                                    <option value="2">Shift 2</option>
                                    <option value="3">Shift 3</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Pilih PRO Number</label>
                                <select id="pro_number" name="pro_number" class="form-select select2-bs5" required>
                                    <option value="">-- Cari PRO --</option>
                                    <?php
                                    $query_pro = "SELECT pro_number FROM t_pro ORDER BY id DESC";
                                    $result_pro = mysqli_query($conn, $query_pro);
                                    while ($row_pro = mysqli_fetch_assoc($result_pro)) {
                                        echo '<option value="' . $row_pro['pro_number'] . '">' . $row_pro['pro_number'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mesin</label>
                                <select name="mesin" class="form-select" required>
                                    <option value="">-- Pilih Mesin --</option>
                                    <?php for($i=1; $i<=8; $i++): ?>
                                        <option value="WM0<?= $i ?>">WM0<?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Merk</label>
                                <select name="merk" class="form-select" required>
                                    <option value="">-- Pilih Merk --</option>
                                    <option value="Beva">Beva</option>
                                    <option value="Osmo">Osmo</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Product Name / Code</label>
                                <select id="prod_code" name="prod_code" class="form-select select2-bs5" required>
                                    <option value="">-- Cari Product Code --</option>
                                    <?php
                                    $query_produk = "SELECT prod_code FROM t_produk ORDER BY prod_code ASC";
                                    $result_produk = mysqli_query($conn, $query_produk);
                                    while ($row_produk = mysqli_fetch_assoc($result_produk)) {
                                        echo '<option value="' . $row_produk['prod_code'] . '">' . $row_produk['prod_code'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Type Coating</label>
                                <select name="type_coating" class="form-select" required>
                                    <option value="">-- Pilih Type --</option>
                                    <option value="LG">LG</option>
                                    <option value="HG">HG</option>
                                    <option value="ZN-AL">ZN-AL</option>
                                    <option value="ULTRA">ULTRA</option>
                                    <option value="BLACK">BLACK</option>
                                    <option value="EP">EP</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Shear Strength</label>
                                <div class="input-group">
                                    <input type="number" name="shear_stg" class="form-control" placeholder="0">
                                    <span class="input-group-text">N</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex gap-2">
                            <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-check-circle"></i> Simpan Data Inspeksi
                            </button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 dengan tema Bootstrap 5
    $('.select2-bs5').select2({
        theme: "bootstrap-5",
        width: '100%',
        placeholder: $(this).data('placeholder'),
    });
});
</script>

</body>
</html>