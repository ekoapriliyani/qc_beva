<?php 
require_once 'functions.php';
$data_inspeksi = get_all_inspeksi_bronjong();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard QC - Bronjong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --maroon: #800000; --maroon-dark: #600000; }
        body { background-color: #f8f9fa; }
        .bg-maroon { background-color: var(--maroon); color: white; }
        .btn-maroon { background-color: var(--maroon); color: white; transition: 0.3s; }
        .btn-maroon:hover { background-color: var(--maroon-dark); color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .text-maroon { color: var(--maroon); }
        .table-container { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .badge-ok { background-color: #198754; color: white; }
        .badge-ng { background-color: #dc3545; color: white; }
        .table thead { background-color: #f2f2f2; color: var(--maroon); }
    </style>
</head>
<body>

<nav class="navbar bg-maroon shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1 text-white"><i class="fas fa-microscope me-2"></i> BEVANANDA QC SYSTEM</span>
        <!-- <div class="text-white small">IT Support & QA Monitoring</div> -->
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="text-maroon fw-bold">DATA INSPEKSI BRONJONG (GABIONS)</h4>
            <p class="text-muted small">Menampilkan riwayat pengujian harian dari Departemen QC</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="form_inspeksi_bronjong.php" class="btn btn-maroon btn-lg shadow-sm">
                <i class="fas fa-plus-circle me-2"></i>Tambah Data Inspeksi
            </a>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Tanggal / Jam</th>
                        <th>Shift</th>
                        <th>No. PRO</th>
                        <th>Operator</th>
                        <th>Ukuran (m)</th>
                        <th>Anyam (mm)</th>
                        <th>Lilitan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data_inspeksi)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">Belum ada data inspeksi tersimpan.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($data_inspeksi as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong><?= date('d/m/Y', strtotime($row['tanggal'])) ?></strong><br>
                                <small class="text-muted"><?= $row['waktu'] ?></small>
                            </td>
                            <td><span class="badge bg-info text-dark">Shift <?= $row['shift'] ?></span></td>
                            <td><span class="fw-bold text-primary"><?= $row['no_pro'] ?></span></td>
                            <td><?= $row['operator'] ?></td>
                            <td><?= $row['lebar_1'] ?> x <?= $row['lebar_2'] ?></td>
                            <td><?= $row['d_anyam'] ?></td>
                            <td><?= $row['jml_lilitan'] ?> (Std: 3)</td>
                            <td class="text-center">
                                <?php if($row['status'] == 'OK'): ?>
                                    <span class="badge badge-ok px-3 py-2"><i class="fas fa-check-circle me-1"></i> OK</span>
                                <?php else: ?>
                                    <span class="badge badge-ng px-3 py-2"><i class="fas fa-times-circle me-1"></i> NG</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-secondary" title="Detail"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Print"><i class="fas fa-print"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="text-center mt-5 mb-4 text-muted small">
    &copy; <?= date('Y') ?> PT Bevananda Mustika - IT Support Department
</footer>

</body>
</html>