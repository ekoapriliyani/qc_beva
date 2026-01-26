<?php include("../koneksi.php"); 
require_once 'functions.php';

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}


// Ambil data user dari session untuk info di dashboard
$userName = $_SESSION["name"];
$userRole = $_SESSION["role"];

if (isset($_POST["save_pro"]) > 0) {
    if (tambah_pro($_POST)) {
        echo "<script>
            alert('PRO Number berhasil disimpan');
        </script>";
    } else {
        echo "<script>
        alert('PRO Number gagal disimpan');
        document.location.href='index.php';
    </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Inspeksi Wiremesh - QC System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-clipboard-check"></i> Form Inspeksi Wiremesh</h2>
        <a href="index.php" style="color: var(--primary-color); text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <form action="" method="POST">
        <label for="">PRO Number</label>
        <input type="text" name="pro_number" required>
        <label for="">QTY Produksi</label>
        <input type="number" name="qty_prod">
        <button type="submit" name="save_pro">Simpan</button>
    </form>

    <hr>

    <form action="proses_simpan_wm.php" method="POST">
        <div class="grid-container">
            <div class="form-group">
                <label for="Inspector">Inspector</label>
                <input type="text" name="inspector" value="<?= $userName; ?>" readonly>
            </div>
            <div class="form-group">
                <label>Hari / Tanggal</label>
                <input type="date" name="hari_tgl" required>
            </div>
            <div class="form-group">
                <label>Shift</label>
                <select name="shift" required>
                    <option value="1">Shift 1</option>
                    <option value="2">Shift 2</option>
                    <option value="3">Shift 3</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">PRO Number</label>
                <select id="pro_number" name="pro_number" class="form-control" required>
                    <option value="">-- Pilih PRO Number --</option>
                    <?php
                    $query_pro = "SELECT pro_number FROM t_pro ORDER BY id DESC";
                    $result_pro = mysqli_query($conn, $query_pro);
                    if ($result_pro && mysqli_num_rows($result_pro) > 0) {
                        while ($row_pro = mysqli_fetch_assoc($result_pro)) {
                            echo '<option value="' . $row_pro['pro_number'] . '">' . $row_pro['pro_number'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Mesin</label>
                <select name="mesin" required>
                    <option value="">-- Pilih Mesin --</option>
                    <option value="WM01">WM01</option>
                    <option value="WM02">WM02</option>
                    <option value="WM03">WM03</option>
                    <option value="WM04">WM04</option>
                    <option value="WM05">WM05</option>
                    <option value="WM06">WM06</option>
                    <option value="WM07">WM07</option>
                    <option value="WM08">WM08</option>
                </select>
            </div>
            <div class="form-group">
                <label>Merk</label>
                <select name="merk" required>
                    <option value="">-- Pilih Merk --</option>
                    <option value="Beva">Beva</option>
                    <option value="Osmo">Osmo</option>
                </select>
            </div>

            <div class="form-group">
    <label>Product Name</label>
    <select id="prod_code" name="prod_code" class="form-control" required>
        <option value="">-- Pilih Prod Code --</option>
        <?php
        $query_produk = "SELECT prod_code FROM t_produk ORDER BY prod_code ASC";
        $result_produk = mysqli_query($conn, $query_produk);
        if ($result_produk && mysqli_num_rows($result_produk) > 0) {
            while ($row_produk = mysqli_fetch_assoc($result_produk)) {
                echo '<option value="' . $row_produk['prod_code'] . '">' . $row_produk['prod_code'] . '</option>';
            }
        }
        ?>
    </select>
</div>

            
            <div class="form-group">
                <label>Type Coating</label>
                <select name="type_coating" required>
                    <option value="">-- Pilih Type --</option>
                    <option value="LG">LG</option>
                    <option value="HG">HG</option>
                    <option value="ZN-AL">ZN-AL</option>
                    <option value="ULTRA">ULTRA</option>
                    <option value="BLACK">BLACK</option>
                    <option value="EP">EP</option>
                </select>
            </div>
        </div>

        <div class="btn-container">
            <button type="reset" class="btn btn-cancel">Reset</button>
            <button type="submit" class="btn btn-submit">Simpan Data Inspeksi</button>
        </div>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#prod_code').select2({
        placeholder: "-- Pilih Prod Code --",
        allowClear: true
    });

    $('#pro_number').select2({
        placeholder: "-- Pilih PRO Number --",
        allowClear: true
    });
});
</script>

</body>
</html>