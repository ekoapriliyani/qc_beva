<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

include_once 'functions.php';

// Ambil data user dari session untuk info di dashboard
$userName = $_SESSION["name"];
$userRole = $_SESSION["role"];

//include("../koneksi.php"); // Pastikan path ke file koneksi benar

// Query untuk mengambil data
// $sql = "SELECT * 
//         FROM t_inspeksi_wm 
//         ORDER BY id_inspeksi";
// $result = mysqli_query($conn, $sql);

$wiremesh = query("SELECT * 
        FROM t_inspeksi_wm 
        ORDER BY id_inspeksi DESC");
?>

<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspeksi Wiremesh (WM) - QC System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<div class="container">
    <div class="header-table">
        <h2>Data Inspeksi Wiremesh (WM)</h2>
        
        <a href="logout.php" class="btn-add"><i class="fas fa-sign-out"></i> logout</a>
    </div>
    <a href="tambah_wm.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Inspeksi</a> <br> <br>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tgl</th>
                    <th>Shift</th>
                    <th>PRO</th>
                    <th>Mesin</th>
                    <th>Merk</th>
                    <th>Product Code</th>
                    <th>Coating</th>
                    <!-- <th>Status</th> -->
                    <!-- <th>Sample</th> -->
                    <th>Total Prod</th>
                    <th>NG</th>
                    <!-- <th>Repair</th> -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach($wiremesh as $row): ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row['hari_tgl']; ?></td>
                        <td><?= $row['shift']; ?></td>
                        <td><?= $row['pro_number']; ?></td>
                        <td><?= $row['mesin']; ?></td>
                        <td><?= $row['merk']; ?></td>
                        <td><?= $row['prod_code']; ?></td>
                        <td><?= $row['type_coating']; ?></td>
                        <td><?= $row['total_produksi']; ?></td>
                        <td><?= $row['jml_ng']; ?></td>
                        <td>
                            <a href="preview.php?id=<?= $row['id_inspeksi']; ?>" class="btn-icon btn-preview"><i class="fas fa-eye"></i></a>
                            <a href="edit.php?id=<?= $row['id_inspeksi']; ?>" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                <?php $no++; ?>
                <?php endforeach; ?>

                
            </tbody>
        </table>
    </div>
</div>

</body>
</html>