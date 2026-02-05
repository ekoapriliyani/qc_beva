<?php
include "koneksi.php";
include "header.php"; // Memanggil Sidebar dan CSS

// Query untuk mengambil data gabungan (Tetap sama)
$sql = "SELECT 
            h.id AS header_id,
            h.tgl,
            h.report_no,
            h.drawing_no,
            p.name AS nama_proyek,
            s.nama AS nama_subkon,
            GROUP_CONCAT(
                CONCAT(
                    '• ', d.part_desc, 
                    ' [AVG: ', d.avg, '] ', 
                    ' (', d.result, ')'
                ) SEPARATOR '<br>'
            ) as rincian_detail,
            COUNT(d.id) as total_item
        FROM t_coating_header h
        JOIN t_project p ON h.id_project = p.id
        JOIN t_subkon s ON h.id_subkon = s.id
        LEFT JOIN t_coating_detail d ON h.id = d.id_coating
        GROUP BY h.id
        ORDER BY h.tgl DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i>Daftar Laporan Coating</h2>
        <p class="text-muted">Manajemen inspeksi coating dan pengujian ketebalan</p>
    </div>
    <a href="tambah_header.php" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus-circle me-1"></i> Buat Laporan Baru
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tanggal</th>
                        <th>Informasi Proyek</th>
                        <th>No. Report / Drawing</th>
                        <th>Ringkasan Detail Item</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                        <td>
                            <div class="fw-bold"><?= date('d M Y', strtotime($row['tgl'])); ?></div>
                            <small class="text-muted"><?= date('H:i', strtotime($row['tgl'])); ?> WIB</small>
                        </td>
                        <td>
                            <div class="fw-bold text-primary"><?= $row['nama_proyek']; ?></div>
                            <div class="small text-muted"><i class="fas fa-hard-hat me-1"></i><?= $row['nama_subkon']; ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border mb-1" style="font-size: 0.85rem;"><?= $row['report_no']; ?></span><br>
                            <small class="text-muted"><i class="fas fa-file-invoice me-1"></i><?= $row['drawing_no']; ?></small>
                        </td>
                        <td>
                            <?php if ($row['rincian_detail']): ?>
                                <div class="small border-start ps-2 border-primary" style="max-height: 100px; overflow-y: auto;">
                                    <?= $row['rincian_detail']; ?>
                                    <div class="mt-1 fw-bold text-success small">Total: <?= $row['total_item']; ?> Item</div>
                                </div>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Belum ada item</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="tambah_detail.php?id_coating=<?= $row['header_id']; ?>" class="btn btn-white btn-sm border" title="Edit/Tambah Detail">
                                    <i class="fas fa-edit text-success"></i> Detail
                                </a>
                                <a href="hapus_header.php?id=<?= $row['header_id']; ?>" class="btn btn-white btn-sm border" title="Hapus Laporan" onclick="return confirm('Hapus laporan ini beserta semua detailnya?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted italic">
                            <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                            Belum ada laporan coating yang tersimpan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; // Memanggil Penutup Tag dan JS ?>