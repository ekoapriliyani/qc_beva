<?php 
include("../koneksi.php"); 
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include_once 'functions.php';

if (isset($_GET['id'])) {
    $id_inspeksi = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT h.*, p.prod_code, p.p_produk, p.l_produk
            FROM t_inspeksi_wm h
            JOIN t_produk p ON h.prod_code = p.prod_code
            WHERE h.id_inspeksi = '$id_inspeksi'";
    $result = mysqli_query($conn, $sql);
    $header = mysqli_fetch_assoc($result);

    if (!$header) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
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
    <title>Inspeksi FG - QC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .card { border: none; border-radius: 15px; }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #4a5568; }
        .summary-box { background-color: #f8f9fa; border-left: 4px solid #198754; padding: 10px 15px; border-radius: 5px; }
        .visual-row { background: #fff; border: 1px solid #dee2e6; padding: 15px; border-radius: 10px; margin-bottom: 10px; position: relative; }
        .btn-remove { position: absolute; top: 10px; right: 10px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-box-open text-success me-2"></i>Inspeksi Finish Good
                </h3>
                <a href="preview.php?id=<?= $id_inspeksi; ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i> Tutup
                </a>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white py-3">
                    <span class="fw-bold"><i class="fas fa-clipboard-check me-2"></i>Form Input Hasil Akhir</span>
                </div>
                
                <div class="card-body p-4">
                    <div class="summary-box mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase small fw-bold">Kode Produk</small>
                                <span class="fw-bold text-success fs-5"><?= $header['prod_code']; ?></span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block text-uppercase small fw-bold">Standar Dimensi</small>
                                <span class="fw-bold fs-5"><?= $header['p_produk']; ?> x <?= $header['l_produk']; ?> mm</span>
                            </div>
                        </div>
                    </div>

                    <form action="proses_simpan_detail_wm_fg.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_inspeksi" value="<?= $id_inspeksi; ?>">
                        
                        <div class="row g-3 mb-4 p-3 bg-light rounded border">
                            <div class="col-md-4">
                                <label class="form-label">Batch / Bundle Number</label>
                                <input type="number" name="batch_number" class="form-control border-primary" required autofocus>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quantity Total Produk</label>
                                <div class="input-group">
                                    <input type="number" name="qty" class="form-control border-primary" placeholder="0" required>
                                    <span class="input-group-text small">Unit</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status Akhir Batch</label>
                                <select name="status" id="statusFG" class="form-select fw-bold border-primary" required onchange="updateStatusColor(this)">
                                    <option value="OK">PASS (OK)</option>
                                    <option value="NG">NOT GOOD (NG)</option>
                                    <option value="REJECT">REJECT</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="fas fa-search me-2"></i>Detail Temuan Visual (Multiple)</h6>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addVisual">
                                <i class="fas fa-plus me-1"></i> Tambah Temuan
                            </button>
                        </div>

                        <div id="visualContainer">
                            <div class="visual-row shadow-sm border-start border-4 border-info">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label small text-muted">JENIS VISUAL</label>
                                        <select name="visual_detail[]" class="form-select" required>
                                            <option value="OK">OK (Normal)</option>
                                            <option value="Crack">Crack</option>
                                            <option value="Karat">Karat</option>
                                            <option value="Las (Lepas/Tidak ngelas)">Las (Lepas/Tidak ngelas)</option>
                                            <option value="CW-LW (Pendek/Bengkok/Putus)">CW-LW (Pendek/Bengkok/Putus)</option>
                                            <option value="Triming">Triming</option>
                                            <option value="Mesh">Mesh</option>
                                            <option value="Handling">Handling</option>
                                        </select>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label small text-muted">KETERANGAN / CATATAN</label>
                                        <input type="text" name="visual_ket[]" class="form-control" placeholder="Tulis detail temuan di sini...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card border-dashed bg-light">
                                <div class="card-body">
                                    <label class="form-label fw-bold"><i class="fas fa-camera me-2"></i>Upload Foto Temuan (Bisa lebih dari 1)</label>
                                    <input type="file" name="foto_fg[]" class="form-control" accept="image/*" multiple>
                                    <div class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Anda dapat memilih beberapa foto sekaligus. Format: JPG, PNG, JPEG.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                            <button type="reset" class="btn btn-light border px-4">Reset</button>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow">
                                <i class="fas fa-save me-2"></i>Simpan Laporan FG
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const container = document.getElementById('visualContainer');
    const addButton = document.getElementById('addVisual');

    // Tambah Baris Baru
    addButton.addEventListener('click', () => {
        const newRow = document.createElement('div');
        newRow.className = 'visual-row shadow-sm border-start border-4 border-info mb-3 animate__animated animate__fadeIn';
        newRow.innerHTML = `
            <button type="button" class="btn btn-link text-danger btn-remove remove-row p-0">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small text-muted">JENIS VISUAL</label>
                    <select name="visual_detail[]" class="form-select" required>
                        <option value="OK">OK (Normal)</option>
                        <option value="Crack">Crack</option>
                        <option value="Karat">Karat</option>
                        <option value="Las (Lepas/Tidak ngelas)">Las (Lepas/Tidak ngelas)</option>
                        <option value="CW-LW (Pendek/Bengkok/Putus)">CW-LW (Pendek/Bengkok/Putus)</option>
                        <option value="Triming">Triming</option>
                        <option value="Mesh">Mesh</option>
                        <option value="Handling">Handling</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label small text-muted">KETERANGAN / CATATAN</label>
                    <input type="text" name="visual_ket[]" class="form-control" placeholder="Tulis detail temuan di sini...">
                </div>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Hapus Baris
    container.addEventListener('click', (e) => {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.visual-row').remove();
        }
    });

    function updateStatusColor(select) {
        if(select.value === 'OK') {
            select.className = 'form-select fw-bold border-success text-success';
        } else if(select.value === 'NG') {
            select.className = 'form-select fw-bold border-warning text-warning';
        } else {
            select.className = 'form-select fw-bold border-danger text-danger';
        }
    }
    
    document.addEventListener("DOMContentLoaded", () => updateStatusColor(document.getElementById('statusFG')));
</script>
</body>
</html>