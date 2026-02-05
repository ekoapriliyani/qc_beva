<?php
include "koneksi.php";

// --- LOGIKA PROSES (Sama seperti sebelumnya) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btn_simpan'])) {
        $nama = mysqli_real_escape_string($conn, $_POST['name']);
        mysqli_query($conn, "INSERT INTO t_project (name) VALUES ('$nama')");
        header("Location: proyek.php?msg=success"); exit;
    }
    if (isset($_POST['btn_update'])) {
        $id = $_POST['id'];
        $nama = mysqli_real_escape_string($conn, $_POST['name']);
        mysqli_query($conn, "UPDATE t_project SET name = '$nama' WHERE id = '$id'");
        header("Location: proyek.php?msg=updated"); exit;
    }
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM t_project WHERE id = '$id'");
    header("Location: proyek.php?msg=deleted"); exit;
}

include "header.php";
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Proyek</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-1"></i> Tambah Proyek
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>NAMA PROYEK</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM t_project ORDER BY id DESC");
                    $list_proyek = []; // Kita tampung datanya untuk modal di luar loop ini
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($res)):
                        $list_proyek[] = $row; // Simpan ke array
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++; ?></td>
                        <td class=""><?= $row['name']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="proyek.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')">
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
        <form action="proyek.php" method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Proyek</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-bold">Nama Proyek</label>
                <input type="text" name="name" class="form-control" placeholder="Input nama..." required>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btn_simpan" class="btn btn-primary px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($list_proyek as $p): ?>
<div class="modal fade" id="editModal<?= $p['id']; ?>" tabindex="-1" aria-labelledby="label<?= $p['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form action="proyek.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="label<?= $p['id']; ?>">Edit Proyek #<?= $p['id']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $p['id']; ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Proyek</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']); ?>" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="btn_update" class="btn btn-success btn-sm px-3">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php include "footer.php"; ?>