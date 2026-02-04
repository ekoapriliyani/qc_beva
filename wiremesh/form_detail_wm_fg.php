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
        <h2><i class="fas fa-microscope"></i> Inspeksi Finish Good</h2>
        <p>ID Inspeksi: <strong><?php echo $id_inspeksi; ?></strong></p>
    </div>

    <form action="proses_simpan_detail_wm_fg.php" method="POST">
        <input type="hidden" name="id_inspeksi" value="<?php echo $id_inspeksi; ?>">
        <div class="grid-container">
            <div class="form-group">
                <label>Visual Detail</label>
                <select name="visual_detail" required>
                    <option value="0">OK</option>
                    <option value="1">Crack</option>
                    <option value="2">Karat</option>
                    <option value="3">Las (Lepas/Tidak ngelas)</option>
                    <option value="4">CW-LW (Pendek/Bengkok/Putus)</option>
                    <option value="5">Triming</option>
                    <option value="6">Mesh</option>
                    <option value="7">Handling</option>
                </select>
            </div>
            <div class="form-group">
                <label>Batch Number</label>
                <input type="number" name="batch_number" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="OK">OK</option>
                    <option value="NG">NG</option>
                    <option value="REJECT">REJECT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="QTY">QTY</label>
                <input type="number" name="qty">
            </div>
        </div>
        <div class="btn-container">
            <a href="preview.php?id=<?php echo $id_inspeksi; ?>" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-submit">Simpan Detail</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnVisualDetail = document.getElementById("btnVisualDetail");
    const visualDetailForm = document.getElementById("visualDetailForm");

    btnVisualDetail.addEventListener("click", function() {
        if (visualDetailForm.style.display === "none") {
            visualDetailForm.style.display = "block";
            btnVisualDetail.innerHTML = '<i class="fas fa-times"></i> Tutup Visual Detail';
        } else {
            visualDetailForm.style.display = "none";
            btnVisualDetail.innerHTML = '<i class="fas fa-eye"></i> Tambah Visual Detail';
        }
    });
});
</script>

</body>
</html>