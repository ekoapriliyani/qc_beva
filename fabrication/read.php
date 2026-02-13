<?php
include "koneksi.php";
include "header.php";

// 1. PENGATURAN PAGINATION
$batas = 20; // Jumlah data per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// 2. LOGIKA PENCARIAN
$keyword = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$where_clause = "";
if ($keyword != '') {
    $where_clause = " WHERE p.name LIKE '%$keyword%' 
                      OR s.nama LIKE '%$keyword%' 
                      OR h.report_no LIKE '%$keyword%' 
                      OR h.pro_number LIKE '%$keyword%' ";
}

// 3. HITUNG TOTAL DATA UNTUK PAGINATION
$query_hitung = "SELECT COUNT(DISTINCT h.id) AS total FROM t_fabrication_header h 
                 JOIN t_project p ON h.id_project = p.id
                 JOIN t_subkon s ON h.id_subkon = s.id 
                 $where_clause";
$res_hitung = mysqli_query($conn, $query_hitung);
$data_hitung = mysqli_fetch_assoc($res_hitung);
$total_data = $data_hitung['total'];
$total_halaman = ceil($total_data / $batas);

// 4. QUERY UTAMA DENGAN LIMIT & OFFSET
$sql = "SELECT 
            h.id AS header_id, h.pro_number, h.qty, h.tgl, h.report_no, h.drawing_no,
            p.name AS nama_proyek, s.nama AS nama_subkon,
            COUNT(d.id) as total_item
        FROM t_fabrication_header h
        JOIN t_project p ON h.id_project = p.id
        JOIN t_subkon s ON h.id_subkon = s.id
        LEFT JOIN t_fabrication_detail d ON h.id = d.id_fabrication
        $where_clause
        GROUP BY h.id
        ORDER BY h.tgl DESC 
        LIMIT $halaman_awal, $batas";

$result = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i>Daftar Laporan Fabrication</h2>
        <p class="text-muted">Manajemen inspeksi fabrication</p>
    </div>
    <a href="tambah_header.php" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus-circle me-1"></i> Buat Laporan Baru
    </a>
</div>

<div class="row mb-3">
    <div class="col-md-5">
        <form action="" method="GET" class="input-group shadow-sm">
            <input type="text" name="q" class="form-control" placeholder="Cari Proyek, Subkon, atau No. Report..." value="<?= htmlspecialchars($keyword); ?>">
            <button class="btn btn-white border" type="submit"><i class="fas fa-search text-primary"></i></button>
            <?php if($keyword != ''): ?>
                <a href="read.php" class="btn btn-white border text-danger" title="Reset Search"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tanggal & PRO</th>
                        <th>QTY</th>
                        <th>Informasi Proyek</th>
                        <th>No. Report / Drawing</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $halaman_awal + 1;
                    if(mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++; ?></td>
                        <td>
                            <div class="fw-bold"><?= date('d M Y', strtotime($row['tgl'])); ?></div>
                            <small class="text-muted">PRO: <?= $row['pro_number']; ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= $row['qty']; ?></span></td>
                        <td>
                            <div class="fw-bold text-primary"><?= $row['nama_proyek']; ?></div>
                            <div class="small text-muted"><i class="fas fa-hard-hat me-1"></i><?= $row['nama_subkon']; ?></div>
                        </td>
                        <td>
                            <div class="small fw-bold text-dark mb-1"><?= $row['report_no']; ?></div>
                            <small class="text-muted"><i class="fas fa-file-invoice me-1"></i><?= $row['drawing_no']; ?></small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="tambah_detail.php?id_fabrication=<?= $row['header_id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit/Tambah Detail">
                                    <i class="fas fa-edit"></i> Detail (<?= $row['total_item']; ?>)
                                </a>
                                <a href="hapus_header.php?id=<?= $row['header_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus laporan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if($total_halaman > 1): ?>
<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= ($halaman <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?halaman=<?= $halaman - 1; ?>&q=<?= $keyword; ?>">Previous</a>
        </li>
        <?php for($x=1; $x<=$total_halaman; $x++): ?>
            <li class="page-item <?= ($halaman == $x) ? 'active' : ''; ?>">
                <a class="page-link" href="?halaman=<?= $x; ?>&q=<?= $keyword; ?>"><?= $x; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?halaman=<?= $halaman + 1; ?>&q=<?= $keyword; ?>">Next</a>
        </li>
    </ul>
</nav>
<?php endif; ?>

<?php include "footer.php"; ?>