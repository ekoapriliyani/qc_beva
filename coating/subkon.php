<?php
include "koneksi.php";

// --- LOGIKA PROSES (CRUD) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tambah Subkon
    if (isset($_POST['btn_simpan'])) {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
        mysqli_query($conn, "INSERT INTO t_subkon (nama, alamat, no_telp) VALUES ('$nama', '$alamat', '$no_telp')");
        header("Location: subkon.php?msg=success"); exit;
    }
    // Update Subkon
    if (isset($_POST['btn_update'])) {
        $id = $_POST['id'];
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
        mysqli_query($conn, "UPDATE t_subkon SET 
        nama = '$nama', 
        alamat = '$alamat', 
        no_telp = '$no_telp'
        WHERE id = '$id'");
        header("Location: subkon.php?msg=updated"); exit;
    }
}

// Hapus Subkon
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM t_subkon WHERE id = '$id'");
    header("Location: subkon.php?msg=deleted"); exit;
}

include "header.php";
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Manajemen Subkon</h2>
            <p class="text-muted">Daftar sub-kontraktor / vendor coating</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-user-plus me-1"></i> Tambah Subkon
        </button>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            Data berhasil <strong><?= ($_GET['msg'] == 'success') ? 'disimpan' : (($_GET['msg'] == 'updated') ? 'diperbarui' : 'dihapus'); ?></strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" width="10%">NO</th>
                        <th>NAMA SUBKON / VENDOR</th>
                        <th>ALAMAT</th>
                        <th>NO TELP</th>
                        <th class="text-center" width="20%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM t_subkon ORDER BY id DESC");
                    $list_subkon = []; 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($res)):
                        $list_subkon[] = $row; 
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++; ?></td>
                        <td class="text-dark"><?= $row['nama']; ?></td>
                        <td class="text-dark"><?= $row['alamat']; ?></td>
                        <td class="text-dark"><?= $row['no_telp']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-success me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="subkon.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus subkon ini? Data laporan terkait mungkin akan terpengaruh.')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="subkon.php" method="POST" class="modal-content shadow border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Subkon Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">NAMA PERUSAHAAN / VENDOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-industry"></i></span>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama subkon..." required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">ALAMAT PERUSAHAAN / VENDOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-home"></i></span>
                        <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat subkon..." required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">NO TELP PERUSAHAAN / VENDOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                        <input type="number" name="no_telp" class="form-control" placeholder="Masukkan no telp subkon..." required>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="btn_simpan" class="btn btn-primary px-4">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($list_subkon as $s): ?>
<div class="modal fade" id="editModal<?= $s['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="subkon.php" method="POST" class="modal-content shadow border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Edit Subkon #<?= $s['id']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" value="<?= $s['id']; ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">NAMA PERUSAHAAN / VENDOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-industry"></i></span>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($s['nama']); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">ALAMAT PERUSAHAAN / VENDOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-home"></i></span>
                        <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($s['alamat']); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">NO TELP PERUSAHAAN / VENDOR</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                        <input type="number" name="no_telp" class="form-control" value="<?= htmlspecialchars($s['no_telp']); ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="btn_update" class="btn btn-success px-4 text-white">Update Data</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php include "footer.php"; ?>