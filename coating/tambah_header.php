<?php 
include "koneksi.php";
include "header.php"; 
?>

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Buat Laporan Inspeksi Baru</h5>
        </div>
        <div class="card-body p-4">
            <form action="proses_header.php" method="POST">
                <!-- <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor PRO</label>
                        <input type="text" name="pro_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">QTY.</label>
                        <input type="number" name="qty" class="form-control" required>
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Proyek</label>
                        <select name="id_project" class="form-control select2-search" required>
                            <option value=""></option> <?php 
                            $res = mysqli_query($conn, "SELECT * FROM t_project ORDER BY name ASC");
                            while($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">Cari berdasarkan nama proyek</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Subkontraktor (Vendor)</label>
                        <select name="id_subkon" class="form-control select2-search" required>
                            <option value=""></option> <?php 
                            $res = mysqli_query($conn, "SELECT * FROM t_subkon ORDER BY nama ASC");
                            while($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">Cari berdasarkan nama vendor/subkon</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tanggal Inspeksi</label>
                        <input type="date" name="tgl" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Report No.</label>
                        <input type="text" name="report_no" class="form-control" placeholder="Contoh: COAT/2024/001" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Drawing No.</label>
                        <input type="text" name="drawing_no" class="form-control" placeholder="Masukkan nomor gambar" required>
                    </div>
                </div>

                <div class="mt-4 d-grid d-md-flex justify-content-md-end">
                    <a href="read.php" class="btn btn-light me-md-2 mb-2 mb-md-0">Batal</a>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        Simpan & Lanjut Input Detail <i class="fas fa-chevron-right ms-2"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>