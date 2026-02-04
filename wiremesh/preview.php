<?php 
include("../koneksi.php"); 
require_once 'functions.php';

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query ambil data detail
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
        echo "<script>
            alert('Data gagal diupdate');
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Inspeksi WM - <?php echo $data['id_inspeksi']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    

<div class="preview-container">
    <div style="margin-bottom: 20px;">
        <a href="form_detail_wm.php?id=<?php echo $data['id_inspeksi']; ?>" class="btn-add" style="background-color: var(--primary-color); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">
            <i class="fas fa-plus-circle"></i> Tambah Detail Inspeksi
        </a>
    </div>
    <div class="report-header">
        <div>
            <h2 style="color: var(--primary-color); margin:0;">LAPORAN INSPEKSI QC</h2>
            <small>ID: <?php echo $data['id_inspeksi']; ?></small>
        </div>
        <div>
            <span class="status-pill <?php echo ($data['status'] == 'OK') ? 'bg-ok' : 'bg-ng'; ?>">
                STATUS: <?php echo $data['status']; ?>
            </span>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h4>Hari / Tanggal</h4>
            <p><?php echo date('d F Y', strtotime($data['hari_tgl'])); ?></p>
        </div>
        <div class="info-box">
            <h4>Shift</h4>
            <p><?php echo $data['shift']; ?></p>
        </div>
        <div class="info-box">
            <h4>Nomor PRO</h4>
            <p><?php echo $data['pro_number']; ?></p>
        </div>
        <div class="info-box">
            <h4>QTY PRO</h4>
            <p><?php echo $data['qty_prod']; ?></p>
        </div>
        <div class="info-box">
            <h4>Mesin</h4>
            <p><?php echo $data['mesin']; ?></p>
        </div>
        <div class="info-box">
            <h4>Merk Produk</h4>
            <p><?php echo $data['merk']; ?></p>
        </div>
    </div>
    <table class="specs-table">
        <tr><th>Product Name</th><td><?php echo $data['prod_code']; ?></td></tr>
        <tr><th>Type Coating</th><td><?php echo $data['type_coating']; ?></td></tr>
        <tr><th>Shear Strength</th><td><?php echo $data['shear_stg'] . ' MPa'; ?></td></tr>
    </table>

    <h3 style="border-bottom: 1px solid var(--primary-color); padding-bottom: 5px; margin-top: 40px; color: var(--primary-color);">
        <i class="fas fa-list-ol"></i> Hasil Inspeksi (WIP)
    </h3>
    
    <div class="table-responsive">
        <table class="specs-table" style="font-size: 12px; margin-top: 10px;">
            <thead>
                    <tr>
                        <th>No</th>
                        <th>Material</th>
                        <th>Operator</th>
                        <th>⌀ Kawat (mm)</th>
                        <th>P x L Produk (mm)</th>
                        <th>P x L Mesh (mm)</th>
                        <th>Δ Diagonal (mm)</th>
                        <th>Torsi</th>
                        <th>Dimensi</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // Query mengambil data dari tabel detail yang berelasi dengan id_inspeksi utama
                $query_detail = mysqli_query($conn, "
                    SELECT d.*, v.visual_detail, v.keterangan 
                    FROM t_inspeksi_wm_detail d
                    LEFT JOIN t_visual_detail v ON d.id_visual_detail = v.id
                    WHERE d.id_inspeksi = '$id'
                    ORDER BY d.id_detail ASC
                ");
                
                if (mysqli_num_rows($query_detail) > 0) {
                    while ($row_det = mysqli_fetch_assoc($query_detail)) {
                    echo "<tr>";
                    echo "<td style='text-align: center;'>".$no++."</td>";
                    echo "<td>".$row_det['material']."</td>";
                    echo "<td>".$row_det['operator_prod']."</td>";
                    echo "<td>".$row_det['d_kawat_act']."</td>";
                    echo "<td>".$row_det['p_produk_act']." x ".$row_det['l_produk_act']."</td>";
                    echo "<td>".$row_det['p_mesh_act']." x ".$row_det['l_mesh_act']."</td>";
                    echo "<td>".$row_det['diagonal']."</td>";
                    echo "<td>".$row_det['torsi_strgh']."</td>";
                    echo "<td>".$row_det['status_dimensi']."</td>";
                    echo "<td>".$row_det['created_at']."</td>";
                    echo "<td style='text-align:center;'>
                            <a href='edit_detail.php?id_detail=".$row_det['id_detail']."&id_main=$id' 
                            style='color:#007bff;' title='Edit'>
                            <i class='fas fa-edit'></i>
                            </a>
                            &nbsp;
                            <a href='hapus_detail.php?id_detail=".$row_det['id_detail']."&id_main=$id' 
                            style='color:#dc3545;' onclick='return confirm(\"Hapus baris ini?\")' title='Delete'>
                            <i class='fas fa-trash'></i>
                            </a>
                        </td>";
                    echo "</tr>";
                }
                } else {
                    echo "<tr><td colspan='9' style='text-align: center; padding: 20px; color: #999;'>Belum ada data sample detail untuk inspeksi ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr><br><br>

    
    <div>
        <a href="form_detail_wm_fg.php?id=<?php echo $data['id_inspeksi']; ?>" class="btn-add" style="background-color: var(--primary-color); color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-size: 14px;">
            <i class="fas fa-plus-circle"></i> Tambah Inspeksi Finish Good
        </a>
    </div>
    <h3 style="border-bottom: 1px solid var(--primary-color); padding-bottom: 5px; margin-top: 40px; color: var(--primary-color);">
        <i class="fas fa-list-ol"></i> Hasil Inspeksi (Finish Good)
    </h3>

        <div class="table-responsive">
            <table class="specs-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Visual Detail</th>
                        <th>Batch Number</th>
                        <th>Status</th>
                        <th>QTY/Roll</th>
                        <th>Created At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_fg = mysqli_query($conn, "SELECT * FROM t_inspeksi_wm_fg WHERE id_inspeksi='$id' ORDER BY id_fg ASC");
                    if (mysqli_num_rows($query_fg) > 0) {
                        while ($row_fg = mysqli_fetch_assoc($query_fg)) {
                            echo "<tr>";
                            echo "<td>".$no++."</td>";
                            echo "<td>".$row_fg['visual_detail']."</td>";
                            echo "<td>".$row_fg['batch_number']."</td>";
                            echo "<td>".$row_fg['status']."</td>";
                            echo "<td>".$row_fg['qty']."</td>";
                            echo "<td>".$row_fg['created_at']."</td>";
                            echo "<td style='text-align:center;'>
                                    <a href='edit_fg.php?id_fg=".$row_fg['id_fg']."&id_main=$id' style='color:#007bff;' title='Edit'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    &nbsp;
                                    <a href='hapus_fg.php?id_fg=".$row_fg['id_fg']."&id_main=$id' style='color:#dc3545;' onclick='return confirm(\"Hapus data ini?\")' title='Delete'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; padding:20px; color:#999;'>Belum ada data Finish Good.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <br><br><hr>
    <h3 style="border-bottom: 1px solid var(--primary-color); padding-bottom: 5px; margin-top: 40px; color: var(--primary-color);">
        <i class="fas fa-list-ol"></i> Pengecekan Hasil Repair Produksi
    </h3>
    <br><hr><br>

    <form action="" method="post" id="formInspeksi">
        <table class="specs-table">
            <input hidden type="number" name="id" value="<?php echo $data['id_inspeksi']; ?>" readonly>
            <tr>
                <th>Jumlah NG</th>
                <td>
                    <div class="form-group">
                        <input type="number" name="jml_ng" 
                            value="<?php echo $data['jml_ng']; ?>" 
                            required disabled>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Jumlah Reject</th>
                <td>
                    <div class="form-group">
                        <input type="number" name="jml_reject" 
                            value="<?php echo $data['jml_reject']; ?>" 
                            required disabled>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Total Produksi per Shift</th>
                <td>
                    <div class="form-group">
                        <input type="number" name="total_produksi" 
                            value="<?php echo $data['total_produksi']; ?>" 
                            required disabled>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td>
                    <div class="form-group">
                        <textarea name="status_repair" disabled><?php echo $data['status_repair']; ?></textarea>
                    </div>
                </td>
            </tr>
        </table>

        <button type="button" id="btnEdit">Edit/Ubah</button>
        <button type="submit" name="update_wm" id="btnUpdate" disabled>Update</button>
    </form>
    
    <div style="margin-top: 30px; display: flex; gap: 10px;">
        <!-- <button onclick="window.print()" class="btn btn-submit btn-print"><i class="fas fa-print"></i> Cetak Laporan</button> -->
        <a href="index.php" class="btn btn-cancel btn-print">Tutup</a>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnEdit   = document.getElementById("btnEdit");
    const btnUpdate = document.getElementById("btnUpdate");
    const form      = document.getElementById("formInspeksi");

    btnEdit.addEventListener("click", function() {
        // aktifkan semua input & textarea
        form.querySelectorAll("input, textarea").forEach(el => {
            if (el.name !== "id") { // biarkan ID tetap readonly
                el.removeAttribute("disabled");
            }
        });

        // aktifkan tombol update
        btnUpdate.removeAttribute("disabled");

        // opsional: disable tombol edit agar tidak ditekan lagi
        btnEdit.setAttribute("disabled", true);
    });
});
</script>
</body>
</html>