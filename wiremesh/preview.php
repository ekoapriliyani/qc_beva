<?php 
include("../koneksi.php"); 

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query ambil data detail
    $query = "SELECT * FROM t_inspeksi_wm WHERE id_inspeksi = '$id'";
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
            <p><?php echo $data['total_produksi']; ?></p>
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
    </table>

    <h3 style="border-bottom: 1px solid var(--primary-color); padding-bottom: 5px; margin-top: 40px; color: var(--primary-color);">
        <i class="fas fa-list-ol"></i> Daftar Hasil Pengukuran (Sample Detail)
    </h3>
    
    <div class="table-responsive">
        <table class="specs-table" style="font-size: 12px; margin-top: 10px;">
            <thead>
                <tr style="background-color: #f9f9f9;">
                    <th style="width: 1%; text-align: center;">No</th>
                    <th>Material</th>
                    <th>D. Kawat (mm)</th>
                    <th>P x L Produk (mm)</th>
                    <th>P x L Mesh (mm)</th>
                    <th>Selisih Diagonal (mm)</th>
                    <th>Shear (MPa)</th>
                    <th>Torsi</th>
                    <th>Visual</th>
                    <th></th>
                    <th class="btn-print" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // Query mengambil data dari tabel detail yang berelasi dengan id_inspeksi utama
                $query_detail = mysqli_query($conn, "SELECT * FROM t_inspeksi_wm_detail WHERE id_inspeksi = '$id' ORDER BY id_detail ASC");
                
                if (mysqli_num_rows($query_detail) > 0) {
                    while ($row_det = mysqli_fetch_assoc($query_detail)) {
                        echo "<tr>";
                        echo "<td style='text-align: center;'>".$no++."</td>";
                        echo "<td>".$row_det['material']."</td>";
                        echo "<td>".$row_det['d_kawat_act']."</td>";
                        echo "<td>".$row_det['p_produk_act']." x ".$row_det['l_produk_act']."</td>";
                        echo "<td>".$row_det['p_mesh_act']." x ".$row_det['l_mesh_act']."</td>";
                        echo "<td>".$row_det['diagonal']."</td>";
                        echo "<td>".$row_det['shear_strght_mpa']."</td>";
                        echo "<td>".$row_det['torsi_strgh']."</td>";
                        echo "<td>".$row_det['visual']."</td>";
                        echo "<td>".$row_det['created_at']."</td>";
                        echo "<td class='btn-print' style='text-align: center;'>
                                <a href='hapus_detail.php?id_detail=".$row_det['id_detail']."&id_main=$id' 
                                   style='color: #dc3545;' 
                                   onclick='return confirm(\"Hapus baris ini?\")'>
                                   <i class='fas fa-edit'></i>
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

    <!-- <h3 style="border-bottom: 1px solid var(--primary-color); padding-bottom: 5px;">Data Produksi</h3> -->
    <form action="proses_inspeksi.php" method="post">
    <table class="specs-table">
        <!-- <tr>
            <th>Jumlah Sample</th>
            <td>
                <input type="number" name="jml_sample_diambil" 
                       value="<?php echo $data['jml_sample_diambil']; ?>" 
                       required>
            </td>
        </tr> -->
        <tr>
            <th>Jumlah NG</th>
            <td>
                <div class="form-group">
                <input type="number" name="jml_ng" 
                       value="<?php echo $data['jml_ng']; ?>" 
                       required>
                </div>
            </td>
        </tr>
        <tr>
            <th>Jumlah Reject</th>
            <td>
                <div class="form-group">
                <input type="number" name="jml_reject" 
                       value="<?php echo $data['jml_reject']; ?>" 
                       required>
                </div>
            </td>
        </tr>
        <tr>
            <th>Total Produksi per Shift</th>
            <td>
                <div class="form-group">
                <input type="number" name="total_produksi" 
                       value="<?php echo $data['total_produksi']; ?>" 
                       required>
                </div>
            </td>
        </tr>
        <tr>
            <th>Status Repair / Catatan</th>
            <td>
                <div class="form-group">
                <textarea name="status_repair"><?php echo $data['status_repair']; ?></textarea>
                </div>
            </td>
        </tr>
    </table>
    <button type="submit">Simpan</button>
</form>
    
    

    <div style="margin-top: 30px; display: flex; gap: 10px;">
        <button onclick="window.print()" class="btn btn-submit btn-print"><i class="fas fa-print"></i> Cetak Laporan</button>
        <a href="index.php" class="btn btn-cancel btn-print">Tutup</a>
    </div>
</div>

</body>
</html>