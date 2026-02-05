<?php
include("../koneksi.php");
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$id_detail = mysqli_real_escape_string($conn, $_GET['id_detail']);
$id_main   = mysqli_real_escape_string($conn, $_GET['id_main']);

// Ambil data detail + relasi visual detail
$query = mysqli_query($conn, "
    SELECT d.*, v.visual_detail, v.keterangan 
    FROM t_inspeksi_wm_detail d
    LEFT JOIN t_visual_detail v ON d.id_visual_detail = v.id
    WHERE d.id_detail = '$id_detail'
");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='preview.php?id=$id_main';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Detail Inspeksi</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<div class="form-container">
    <h2>Edit Detail Inspeksi</h2>
    <form action="proses_update_detail.php" method="POST">
        <input type="hidden" name="id_detail" value="<?= $data['id_detail']; ?>">
        <input type="hidden" name="id_main" value="<?= $id_main; ?>">
    <div class="grid-container">
        <div class="form-group">
            <label>Material</label>
            <input type="text" name="material" value="<?= htmlspecialchars($data['material']); ?>" required>
        </div>
        <div class="form-group">
            <label>Operator Produksi</label>
            <input type="text" name="operator_prod" value="<?= htmlspecialchars($data['operator_prod']); ?>" required>
        </div>
        <div class="form-group">
            <label>Diameter Kawat Act (mm)</label>
            <input type="number" step="0.01" name="d_kawat_act" value="<?= $data['d_kawat_act']; ?>" required>
        </div>
        <div class="form-group">
            <label>Panjang Produk Act (mm)</label>
            <input type="number" name="p_produk_act" value="<?= $data['p_produk_act']; ?>" required>
        </div>
        <div class="form-group">
            <label>Lebar Produk Act (mm)</label>
            <input type="number" name="l_produk_act" value="<?= $data['l_produk_act']; ?>" required>
        </div>
        <div class="form-group">
            <label>Panjang Mesh Act (mm)</label>
            <input type="number" name="p_mesh_act" value="<?= $data['p_mesh_act']; ?>" required>
        </div>
        <div class="form-group">
            <label>Lebar Mesh Act (mm)</label>
            <input type="number" name="l_mesh_act" value="<?= $data['l_mesh_act']; ?>" required>
        </div>
        <div class="form-group">
            <label>Selisih Diagonal (mm)</label>
            <input type="number" name="diagonal" value="<?= $data['diagonal']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Torsi Strength</label>
            <select name="torsi_strgh" required>
            <option value="OK" <?= $data['torsi_strgh']=="OK"?"selected":""; ?>>OK</option>
            <option value="NG" <?= $data['torsi_strgh']=="NG"?"selected":""; ?>>NG</option>
            </select>
        </div> 
        <div class="form-group">
            <label>Status Dimensi</label>
            <select name="status_dimensi" required>
                <option value="OK" <?= $data['status_dimensi']=="OK"?"selected":""; ?>>OK</option>
                <option value="NG" <?= $data['status_dimensi']=="NG"?"selected":""; ?>>NG</option>
            </select>
        </div>
        <div class="form-group">
            <label>Visual Detail</label>
            <select name="visual_detail" required>
                <option value="0" <?= $data['visual_detail']=="0"?"selected":""; ?>>OK</option>
                <option value="1" <?= $data['visual_detail']=="1"?"selected":""; ?>>Crack</option>
                <option value="2" <?= $data['visual_detail']=="2"?"selected":""; ?>>Karat</option>
                <option value="3" <?= $data['visual_detail']=="3"?"selected":""; ?>>Las (Lepas/Tidak ngelas)</option>
                <option value="4" <?= $data['visual_detail']=="4"?"selected":""; ?>>CW-LW (Pendek/Bengkok/Putus)</option>
                <option value="5" <?= $data['visual_detail']=="5"?"selected":""; ?>>Triming</option>
                <option value="6" <?= $data['visual_detail']=="6"?"selected":""; ?>>Mesh</option>
                <option value="7" <?= $data['visual_detail']=="7"?"selected":""; ?>>Handling</option>
            </select>
        </div>
        <!-- <div class="form-group">
            <label>Visual Detail</label>
            <input type="text" name="visual_detail" value="<?= htmlspecialchars($data['visual_detail']); ?>">
        </div> -->
        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" value="<?= htmlspecialchars($data['keterangan']); ?>">
        </div>
    </div>
        <button type="submit" class="btn btn-submit">Update</button>
        <a href="preview.php?id=<?= $id_main; ?>" class="btn btn-cancel">Batal</a>
    </form>
</div>
</body>
</html>