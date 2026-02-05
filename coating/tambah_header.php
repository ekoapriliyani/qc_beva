<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">Input Header Coating</div>
        <div class="card-body">
            <form action="proses_header.php" method="POST">
                <div class="mb-3">
                    <label>Proyek</label>
                    <select name="id_project" class="form-control" required>
                        <?php 
                        include "koneksi.php";
                        $res = mysqli_query($conn, "SELECT * FROM t_project");
                        while($row = mysqli_fetch_assoc($res)) echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Subkontraktor</label>
                    <select name="id_subkon" class="form-control" required>
                        <?php 
                        $res = mysqli_query($conn, "SELECT * FROM t_subkon");
                        while($row = mysqli_fetch_assoc($res)) echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Tanggal</label><input type="date" name="tgl" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Report No</label><input type="text" name="report_no" class="form-control" required></div>
                    <div class="col-md-4 mb-3"><label>Drawing No</label><input type="text" name="drawing_no" class="form-control" required></div>
                </div>
                <button type="submit" class="btn btn-success">Simpan & Lanjut Input Detail</button>
            </form>
        </div>
    </div>
</div>