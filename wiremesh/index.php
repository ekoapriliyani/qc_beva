<?php
include("../koneksi.php"); // Pastikan path ke file koneksi benar

// Query untuk mengambil data
$now = date("Y-m-d");
$sql = "SELECT * 
        FROM t_inspeksi_wm 
        -- WHERE hari_tgl = '$now'
        ORDER BY id_inspeksi";
$result = mysqli_query($conn, $sql);

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
        <a href="tambah_wm.php" class="btn-add"><i class="fas fa-plus"></i> Tambah Inspeksi</a>
    </div>

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
                    <th>Status</th>
                    <th>Sample</th>
                    <th>Total Prod</th>
                    <th>NG</th>
                    <th>Repair</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $no++;
                        // Logika warna badge status
                        $status_class = (strtoupper($row['status']) == 'OK') ? 'status-ok' : 'status-ng';
                        
                        echo "<tr>";
                        echo "<td>".$no."</td>";
                        echo "<td>".$row['hari_tgl']."</td>";
                        echo "<td>".$row['shift']."</td>";
                        echo "<td>".$row['pro']."</td>";
                        echo "<td>".$row['mesin']."</td>";
                        echo "<td>".$row['merk']."</td>";
                        echo "<td>".$row['prod_code']."</td>";
                        echo "<td>".$row['type_coating']."</td>";
                        echo "<td><span class='status-badge $status_class'>".$row['status']."</span></td>";
                        echo "<td>".$row['jml_sample_diambil']."</td>";
                        echo "<td>".$row['total_produksi']."</td>";
                        echo "<td>".$row['jml_ng']."</td>";
                        echo "<td>".$row['status_repair']."</td>";
                        echo "<td class='action-btns'>
                                <a href='preview.php?id=".$row['id_inspeksi']."' class='btn-icon btn-preview' title='Preview'><i class='fas fa-eye'></i></a>
                                <a href='edit.php?id=".$row['id_inspeksi']."' class='btn-icon btn-edit' title='Edit'><i class='fas fa-edit'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='15' style='text-align:center;'>Belum ada data.</td></tr>";
                }
                
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>