<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include_once 'functions.php';

// Konfigurasi Pagination
$jumlahDataPerHalaman = 10;
$halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Logika Pencarian
$keyword = "";
if (isset($_GET["search"])) {
    $keyword = $_GET["search"];
    $queryCondition = "WHERE inspector LIKE '%$keyword%' OR pro_number LIKE '%$keyword%' OR prod_code LIKE '%$keyword%'";
} else {
    $queryCondition = "";
}

// Hitung total data untuk pagination
$totalData = count(query("SELECT * FROM t_inspeksi_wm $queryCondition"));
$jumlahHalaman = ceil($totalData / $jumlahDataPerHalaman);

// Ambil data dengan LIMIT
$wiremesh = query("SELECT * FROM t_inspeksi_wm $queryCondition ORDER BY id_inspeksi DESC LIMIT $awalData, $jumlahDataPerHalaman");
?>

<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspeksi Wiremesh (WM) - QC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0 text-primary">Data Inspeksi Wiremesh (WM)</h2>
                <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="tambah_wm.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Inspeksi</a>
                </div>
                <div class="col-md-6">
                    <form action="" method="get" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="" value="<?= $keyword; ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered border-light">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Tgl</th>
                            <th>Inspector</th>
                            <th>Shift</th>
                            <th>PRO</th>
                            <th>Mesin</th>
                            <th>Merk</th>
                            <th>Code</th>
                            <th>Coating</th>
                            <th>Shear</th>
                            <th>Prod per shift</th>
                            <th>NG</th>
                            <th>Reject</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $awalData + 1; ?>
                        <?php if (empty($wiremesh)) : ?>
                            <tr>
                                <td colspan="13" class="text-center text-danger">Data tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach($wiremesh as $row): ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $row['hari_tgl']; ?></td>
                            <td><?= $row['inspector']; ?></td>
                            <td><?= $row['shift']; ?></td>
                            <td><strong><?= $row['pro_number']; ?></strong></td>
                            <td><?= $row['mesin']; ?></td>
                            <td><?= $row['merk']; ?></td>
                            <td><?= $row['prod_code']; ?></td>
                            <td><?= $row['type_coating']; ?></td>
                            <td><?= $row['shear_stg']; ?></td>
                            <td><?= $row['total_produksi']; ?></td>
                            <td><?= $row['jml_ng']; ?></td>
                            <td><?= $row['jml_reject']; ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="preview.php?id=<?= $row['id_inspeksi']; ?>" class="btn btn-info text-white"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?= $row['id_inspeksi']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php $no++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-3">
                    <?php if($halamanAktif > 1) : ?>
                        <li class="page-item"><a class="page-item"><a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>&search=<?= $keyword; ?>">Previous</a></li>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                        <li class="page-item <?= ($i == $halamanAktif) ? 'active' : ''; ?>">
                            <a class="page-link" href="?halaman=<?= $i; ?>&search=<?= $keyword; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if($halamanAktif < $jumlahHalaman) : ?>
                        <li class="page-item"><a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>&search=<?= $keyword; ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>