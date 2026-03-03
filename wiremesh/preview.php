<?php 
include("../koneksi.php"); 
require_once 'functions.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM t_inspeksi_wm 
              JOIN t_pro ON t_pro.pro_number = t_inspeksi_wm.pro_number 
              WHERE id_inspeksi = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

if (isset($_POST["update_wm"])) {
    if (update_wm($_POST)) {
        echo "<script>
            alert('Data berhasil diupdate');
            window.location.href='preview.php?id=$id';
        </script>";
    } else {
        echo "<script>alert('Data gagal diupdate');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Inspeksi - QC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .info-label { color: #6c757d; font-size: 0.85rem; font-weight: 600; margin-bottom: 2px; }
        .info-value { color: #333; font-weight: 700; font-size: 1rem; }
        .status-badge { padding: 8px 16px; border-radius: 50px; font-weight: bold; }
        .bg-ok { background-color: #d1e7dd; color: #0f5132; }
        .bg-ng { background-color: #f8d7da; color: #842029; }
        .table-custom { font-size: 0.85rem; }
        .card-header-title { border-left: 4px solid #0d6efd; padding-left: 15px; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        <div>
            <a href="form_detail_wm.php?id=<?= $id; ?>" class="btn btn-primary btn-sm mx-1">
                <i class="fas fa-plus-circle"></i> Tambah WIP
            </a>
            <a href="form_detail_wm_fg.php?id=<?= $id; ?>" class="btn btn-success btn-sm mx-1">
                <i class="fas fa-plus-circle"></i> Tambah FG
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="card-header-title">
                <h4 class="mb-0 fw-bold text-uppercase">Laporan Inspeksi QC</h4>
                <small class="text-muted">ID Transaksi: <strong>#<?= $data['id_inspeksi']; ?></strong></small>
            </div>
            <span class="status-badge <?= ($data['status'] == 'OK') ? 'bg-ok' : 'bg-ng'; ?>">
                <i class="fas <?= ($data['status'] == 'OK') ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i> 
                STATUS: <?= $data['status']; ?>
            </span>
        </div>
        
        <div class="card-body">
            <div class="row g-3 mb-4 text-center text-md-start">
                <div class="col-6 col-md-3">
                    <div class="info-label text-uppercase">Inspektor</div>
                    <div class="info-value"><?= $data['inspector']; ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-label text-uppercase">Tanggal</div>
                    <div class="info-value"><?= date('d M Y', strtotime($data['hari_tgl'])); ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-label text-uppercase">Shift / Mesin</div>
                    <div class="info-value"><?= $data['shift']; ?> / <?= $data['mesin']; ?></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="info-label text-uppercase">Nomor PRO</div>
                    <div class="info-value text-primary"><?= $data['pro_number']; ?></div>
                </div>
            </div>

            <hr>

            <div class="row mt-4">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><th class="text-muted fw-normal" width="40%">Product Name</th><td>: <?= $data['prod_code']; ?></td></tr>
                        <tr><th class="text-muted fw-normal">QTY PRO</th><td>: <?= $data['qty_prod']; ?> Unit</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><th class="text-muted fw-normal" width="40%">Type Coating</th><td>: <?= $data['type_coating']; ?></td></tr>
                        <tr><th class="text-muted fw-normal">Shear Strength</th><td>: <span class="badge bg-light text-dark border"><?= $data['shear_stg']; ?> MPa</span></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><h6 class="mb-0 fw-bold"><i class="fas fa-microscope text-primary me-2"></i>Hasil Inspeksi WIP (Work In Progress)</h6></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Material</th>
                            <th>Operator</th>
                            <th>⌀ Kawat</th>
                            <th>P x L Produk</th>
                            <th>P x L Mesh</th>
                            <th>Diagonal</th>
                            <th>Dimensi</th>
                            <th>Created at</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query_detail = mysqli_query($conn, "SELECT d.* FROM t_inspeksi_wm_detail d WHERE d.id_inspeksi = '$id' ORDER BY d.id_detail ASC");
                        if (mysqli_num_rows($query_detail) > 0) {
                            while ($row_det = mysqli_fetch_assoc($query_detail)) {
                                echo "<tr>";
                                echo "<td class='ps-3'>".$no++."</td>";
                                echo "<td><span class='badge bg-secondary'>".$row_det['material']."</span></td>";
                                echo "<td>".$row_det['operator_prod']."</td>";
                                echo "<td>".$row_det['d_kawat_act']." mm</td>";
                                echo "<td>".$row_det['p_produk_act']."x".$row_det['l_produk_act']."</td>";
                                echo "<td>".$row_det['p_mesh_act']."x".$row_det['l_mesh_act']."</td>";
                                echo "<td>".$row_det['diagonal']."</td>";
                                echo "<td>".($row_det['status_dimensi'] == 'OK' ? '<span class="text-success fw-bold">OK</span>' : '<span class="text-danger fw-bold">NG</span>')."</td>";
                                echo "<td>".$row_det['created_at']."</td>";
                                echo "<td class='text-center'>
                                        <a href='edit_detail.php?id_detail=".$row_det['id_detail']."&id_main=$id' class='text-warning mx-1'><i class='fas fa-edit'></i></a>
                                        <a href='hapus_detail.php?id_detail=".$row_det['id_detail']."&id_main=$id' class='text-danger mx-1' onclick='return confirm(\"Hapus?\")'><i class='fas fa-trash'></i></a>
                                      </td>";
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center py-4 text-muted'>Belum ada data sample WIP</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><h6 class="mb-0 fw-bold"><i class="fas fa-box text-success me-2"></i>Hasil Inspeksi Finish Good</h6></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Batch Number</th>
                            <th>Visual Detail</th>
                            <th>Status</th>
                            <th>QTY/Roll</th>
                            <th>Created at</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query_fg = mysqli_query($conn, "SELECT * FROM t_inspeksi_wm_fg WHERE id_inspeksi='$id' ORDER BY id_fg ASC");
                        if (mysqli_num_rows($query_fg) > 0) {
                            while ($row_fg = mysqli_fetch_assoc($query_fg)) {
                                echo "<tr>";
                                echo "<td class='ps-3'>".$no++."</td>";
                                echo "<td><strong>".$row_fg['batch_number']."</strong></td>";
                                echo "<td>".$row_fg['visual_detail']."</td>";

                                // Tentukan warna badge berdasarkan status
                                $badgeClass = '';
                                if ($row_fg['status'] == 'OK') {
                                    $badgeClass = 'bg-success';
                                } elseif ($row_fg['status'] == 'NG') {
                                    $badgeClass = 'bg-warning';
                                } elseif ($row_fg['status'] == 'REJECT') {
                                    $badgeClass = 'bg-danger';
                                } else {
                                    $badgeClass = 'bg-secondary'; // fallback jika status tidak dikenali
                                }

                                echo "<td><span class='badge $badgeClass'>".$row_fg['status']."</span></td>";
                                echo "<td>".$row_fg['qty']."</td>";
                                echo "<td>".$row_fg['created_at']."</td>";
                                echo "<td class='text-center'>
                                        <a href='edit_fg.php?id_fg=".$row_fg['id_fg']."&id_main=$id' class='text-warning mx-1'><i class='fas fa-edit'></i></a>
                                        <a href='hapus_fg.php?id_fg=".$row_fg['id_fg']."&id_main=$id' class='text-danger mx-1' onclick='return confirm(\"Hapus?\")'><i class='fas fa-trash'></i></a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Belum ada data Finish Good</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-warning">
        <div class="card-header bg-warning text-dark fw-bold"><i class="fas fa-tools me-2"></i>Hasil Produksi 1 Shift</div>
        <div class="card-body">
            <form action="" method="post" id="formInspeksi">
                <input hidden type="number" name="id" value="<?= $data['id_inspeksi']; ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Jumlah NG</label>
                        <input type="number" name="jml_ng" class="form-control" value="<?= $data['jml_ng']; ?>" required disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Jumlah Reject</label>
                        <input type="number" name="jml_reject" class="form-control" value="<?= $data['jml_reject']; ?>" required disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Total Produksi per Shift</label>
                        <input type="number" name="total_produksi" class="form-control" value="<?= $data['total_produksi']; ?>" required disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Catatan / Status Repair</label>
                        <textarea name="status_repair" class="form-control" rows="2" disabled><?= $data['status_repair']; ?></textarea>
                    </div>
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="button" id="btnEdit" class="btn btn-outline-warning"><i class="fas fa-edit"></i> Edit Laporan Utama</button>
                    <button type="submit" name="update_wm" id="btnUpdate" class="btn btn-primary px-4" disabled><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnEdit = document.getElementById("btnEdit");
    const btnUpdate = document.getElementById("btnUpdate");
    const form = document.getElementById("formInspeksi");

    btnEdit.addEventListener("click", function() {
        form.querySelectorAll("input, textarea").forEach(el => {
            if (el.name !== "id") el.removeAttribute("disabled");
        });
        btnUpdate.removeAttribute("disabled");
        btnEdit.setAttribute("disabled", true);
        form.querySelector('input[name="jml_ng"]').focus();
    });
});
</script>
</body>
</html>