<?php include("../koneksi.php"); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Inspeksi Wiremesh - QC System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-clipboard-check"></i> Form Inspeksi Wiremesh</h2>
        <a href="index.php" style="color: var(--primary-color); text-decoration: none;"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <form action="proses_simpan_wm.php" method="POST">
        <div class="grid-container">
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
                <label>Nomor PRO</label>
                <input type="text" name="pro" placeholder="Contoh: PRO202601001" required>
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
                <select name="prod_code" required>
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

            <!-- <div class="form-group">
                <label>Status Inspeksi Akhir</label>
                <select name="status" required>
                    <option value="OK">OK</option>
                    <option value="NG">NG</option>
                </select>
            </div> -->

            <!-- <div class="form-group">
                <label>Jumlah Sample Diambil</label>
                <input type="number" name="jml_sample_diambil" min="0" required>
            </div> -->
            <div class="form-group">
                <label>QTY PRO (Pcs/Roll)</label>
                <input type="number" name="total_produksi" min="0" required>
            </div>
            <!-- <div class="form-group">
                <label>Jumlah NG (Reject)</label>
                <input type="number" name="jml_ng" min="0" value="0">
            </div> -->

            <!-- <div class="form-group full-width">
                <label>Status Repair / Keterangan</label>
                <textarea name="status_repair" rows="3" placeholder="Tulis catatan repair jika ada..."></textarea>
            </div> -->
        </div>

        <div class="btn-container">
            <button type="reset" class="btn btn-cancel">Reset</button>
            <button type="submit" class="btn btn-submit">Simpan Data Inspeksi</button>
        </div>
    </form>
</div>

</body>
</html>