<?php 
include("../koneksi.php"); 
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include_once 'functions.php';

$userName = $_SESSION["user_name"];
$userRole = $_SESSION["role"];

if (isset($_GET['id'])) {
    $id_inspeksi = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "SELECT h.*, p.prod_code, p.jarak_mesh, p.d_kawat, p.tol_min, p.tol_plus, 
                   p.p_produk, p.l_produk, p.p_mesh, p.l_mesh, p.tol
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .readonly-field { background-color: #e9ecef !important; font-weight: 600; color: #495057; }
        .section-title { 
            border-bottom: 2px solid #0d6efd; 
            padding-bottom: 5px; 
            color: #0d6efd; 
            font-size: 0.9rem; 
            text-transform: uppercase; 
            font-weight: bold;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .card { border: none; border-radius: 12px; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #555; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark"><i class="fas fa-microscope text-primary"></i> Tambah Detail Pengukuran</h2>
            <p class="text-muted">ID Inspeksi Utama: <span class="badge bg-primary">#<?php echo $id_inspeksi; ?></span></p>
        </div>
        <a href="preview.php?id=<?php echo $id_inspeksi; ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="proses_simpan_detail_wm.php" method="POST">
        <input type="hidden" name="id_inspeksi" value="<?php echo $id_inspeksi; ?>">
        
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="section-title"><i class="fas fa-info-circle"></i> Standar Referensi</div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">PRO Number</label>
                                <input type="text" class="form-control readonly-field" value="<?= $header['pro_number']; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product Code</label>
                                <input type="text" class="form-control readonly-field" value="<?= $header['prod_code']; ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Diameter Kawat Standar</label>
                                <div class="input-group">
                                    <input type="text" class="form-control readonly-field" value="<?= $header['d_kawat']; ?>" readonly>
                                    <span class="input-group-text">mm</span>
                                    <span class="input-group-text small text-muted"><?= $header['tol_min']; ?> / <?= $header['tol_plus']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">P x L Produk Standar</label>
                                <input type="text" class="form-control readonly-field" value="<?= $header['p_produk'] . ' x ' . $header['l_produk']; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">P x L Mesh Standar</label>
                                <input type="text" class="form-control readonly-field" value="<?= $header['p_mesh'] . ' x ' . $header['l_mesh']; ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Toleransi P x L</label>
                                <input type="text" class="form-control readonly-field" value="<?= '± '. $header['tol'] . ' mm'; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="section-title text-success" style="border-color: #198754;"><i class="fas fa-edit"></i> Hasil Pengukuran Aktual</div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Lot ID Material</label>
                                <input type="text" name="material" class="form-control border-primary" placeholder="Contoh: LOT-001" autofocus required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Operator Produksi</label>
                                <input type="text" name="operator_prod" class="form-control border-primary" placeholder="Nama Lengkap" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Diameter Kawat Act (mm)</label>
                                <input type="number" step="0.01" name="d_kawat_act" class="form-control form-control-lg" placeholder="0.00" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Panjang Produk Act (mm)</label>
                                <input type="number" name="p_produk_act" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lebar Produk Act (mm)</label>
                                <input type="number" name="l_produk_act" class="form-control" placeholder="0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Panjang Mesh Act (mm)</label>
                                <input type="number" name="p_mesh_act" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lebar Mesh Act (mm)</label>
                                <input type="number" name="l_mesh_act" class="form-control" placeholder="0" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Selisih Diagonal (mm)</label>
                                <input type="number" name="diagonal" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Torsi Strength</label>
                                <select name="torsi_strgh" class="form-select border-info" required>
                                    <option value="OK">OK</option>
                                    <option value="NG">NG</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status Dimensi</label>
                                <select name="status_dimensi" id="status_dimensi" class="form-select fw-bold bg-light" required>
                                    <option value="OK">OK</option>
                                    <option value="NG">NG</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="btnVisualDetail" class="btn btn-info w-100 text-white fw-bold">
                                <i class="fas fa-eye"></i> Tambah Visual Detail (Opsional)
                            </button>
                        </div>

                        <div id="visualDetailForm" class="mt-3 p-3 border rounded bg-light" style="display:none;">
                            <h6 class="fw-bold text-info mb-3"><i class="fas fa-search"></i> Pengecekan Visual</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Visual Detail</label>
                                    <select name="visual_detail" class="form-select">
                                        <option value="OK">OK</option>
                                        <option value="Crack">Crack</option>
                                        <option value="Karat">Karat</option>
                                        <option value="Las (Lepas/Tidak ngelas)">Las (Lepas/Tidak ngelas)</option>
                                        <option value="CW-LW (Pendek/Bengkok/Putus)">CW-LW (Pendek/Bengkok/Putus)</option>
                                        <option value="Triming">Triming</option>
                                        <option value="Mesh">Mesh</option>
                                        <option value="Handling">Handling</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="visual_qty" class="form-control" value="0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Detail temuan visual..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save"></i> Simpan Detail
                            </button>
                            <button type="reset" class="btn btn-light border px-4">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const dKawatAct    = document.querySelector("input[name='d_kawat_act']");
    const pMeshAct     = document.querySelector("input[name='p_mesh_act']");
    const lMeshAct     = document.querySelector("input[name='l_mesh_act']");
    const torsiStrgh   = document.querySelector("select[name='torsi_strgh']");
    const statusDimensi = document.getElementById("status_dimensi");

    // Ambil nilai standar & toleransi
    const dStandar   = parseFloat("<?= $header['d_kawat']; ?>");
    const tolMin     = parseFloat("<?= $header['tol_min']; ?>");
    const tolPlus    = parseFloat("<?= $header['tol_plus']; ?>");
    const pMeshStd   = parseFloat("<?= $header['p_mesh']; ?>");
    const lMeshStd   = parseFloat("<?= $header['l_mesh']; ?>");
    const tolPL      = parseFloat("<?= $header['tol']; ?>");

    function checkStatus() {
        let status = "OK";

        // 1. Cek Diameter Kawat
        const dVal = parseFloat(dKawatAct.value);
        if (!isNaN(dVal)) {
            if (dVal < (dStandar - tolMin) || dVal > (dStandar + tolPlus)) status = "NG";
        }

        // 2. Cek Mesh Panjang
        const pMeshVal = parseFloat(pMeshAct.value);
        if (!isNaN(pMeshVal)) {
            if (pMeshVal < (pMeshStd - tolPL) || pMeshVal > (pMeshStd + tolPL)) status = "NG";
        }

        // 3. Cek Mesh Lebar
        const lMeshVal = parseFloat(lMeshAct.value);
        if (!isNaN(lMeshVal)) {
            if (lMeshVal < (lMeshStd - tolPL) || lMeshVal > (lMeshStd + tolPL)) status = "NG";
        }

        // 4. Cek Torsi
        if (torsiStrgh.value === "NG") status = "NG";

        statusDimensi.value = status;
        
        // Ganti warna background status agar jelas
        if(status === "NG") {
            statusDimensi.classList.replace("bg-light", "bg-danger");
            statusDimensi.classList.add("text-white");
        } else {
            statusDimensi.classList.replace("bg-danger", "bg-light");
            statusDimensi.classList.remove("text-white");
        }
    }

    [dKawatAct, pMeshAct, lMeshAct].forEach(el => {
        el.addEventListener("input", checkStatus);
    });
    torsiStrgh.addEventListener("change", checkStatus);

    // Toggle Visual Detail
    const btnVisualDetail = document.getElementById("btnVisualDetail");
    const visualDetailForm = document.getElementById("visualDetailForm");

    btnVisualDetail.addEventListener("click", function() {
        if (visualDetailForm.style.display === "none") {
            visualDetailForm.style.display = "block";
            btnVisualDetail.classList.replace("btn-info", "btn-secondary");
            btnVisualDetail.innerHTML = '<i class="fas fa-times"></i> Tutup Visual Detail';
        } else {
            visualDetailForm.style.display = "none";
            btnVisualDetail.classList.replace("btn-secondary", "btn-info");
            btnVisualDetail.innerHTML = '<i class="fas fa-eye"></i> Tambah Visual Detail (Opsional)';
        }
    });
});
</script>

</body>
</html>