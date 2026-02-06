<?php
include "koneksi.php";
include "header.php";

// Menangkap filter dari URL
$id_project = isset($_GET['id_project']) ? $_GET['id_project'] : '';
$id_subkon = isset($_GET['id_subkon']) ? $_GET['id_subkon'] : '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-chart-line text-primary me-2"></i>Laporan Rekapitulasi Coating</h2>
    <button class="btn btn-outline-secondary" onclick="window.print()">
        <i class="fas fa-print me-1"></i> Cetak Halaman
    </button>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Filter Proyek</label>
                <select name="id_project" class="form-select select2-search">
                    <option value="">Semua Proyek</option>
                    <?php 
                    $p_res = mysqli_query($conn, "SELECT * FROM t_project ORDER BY name ASC");
                    while($p = mysqli_fetch_assoc($p_res)) {
                        $sel = ($p['id'] == $id_project) ? 'selected' : '';
                        echo "<option value='{$p['id']}' $sel>{$p['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Filter Subkon</label>
                <select name="id_subkon" class="form-select select2-search">
                    <option value="">Semua Subkon</option>
                    <?php 
                    $s_res = mysqli_query($conn, "SELECT * FROM t_subkon ORDER BY nama ASC");
                    while($s = mysqli_fetch_assoc($s_res)) {
                        $sel = ($s['id'] == $id_subkon) ? 'selected' : '';
                        echo "<option value='{$s['id']}' $sel>{$s['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Data Header (Project & Subkon)</th>
                        <th>Detail Item (Part & Specs)</th>
                        <th>Ketebalan (DFT)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query kompleks yang menggabungkan 4 tabel
                    $where_clause = " WHERE 1=1 ";
                    if ($id_project != '') $where_clause .= " AND h.id_project = '$id_project' ";
                    if ($id_subkon != '') $where_clause .= " AND h.id_subkon = '$id_subkon' ";

                    $sql = "SELECT 
                                h.report_no, h.tgl, 
                                p.name as proyek, 
                                s.nama as subkon,
                                d.part_desc, d.qty, d.avg, d.result
                            FROM t_coating_detail d
                            JOIN t_coating_header h ON d.id_coating = h.id
                            JOIN t_project p ON h.id_project = p.id
                            JOIN t_subkon s ON h.id_subkon = s.id
                            $where_clause
                            ORDER BY h.tgl DESC, d.id ASC";

                    $res = mysqli_query($conn, $sql);
                    $no = 1;

                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)):
                            $badge_color = ($row['result'] == 'ACC') ? 'bg-success' : 'bg-danger';
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td>
                                <div class="fw-bold"><?= $row['proyek']; ?></div>
                                <small class="text-muted"><?= $row['report_no']; ?> | <?= date('d/m/Y', strtotime($row['tgl'])); ?></small><br>
                                <span class="badge bg-light text-dark border mt-1">Vendor: <?= $row['subkon']; ?></span>
                            </td>
                            <td>
                                <strong><?= $row['part_desc']; ?></strong><br>
                                <small>Quantity: <?= $row['qty']; ?> pcs</small>
                            </td>
                            <td class="text-center fw-bold text-primary">
                                <?= $row['avg']; ?> <small class="text-muted">µm</small>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $badge_color; ?>"><?= $row['result']; ?></span>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5'>Pilih filter dan klik cari untuk menampilkan data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>