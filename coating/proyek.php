<?php
include "koneksi.php";

// --- KONFIGURASI PAGINATION & SEARCH ---
$limit = 10; // Jumlah data per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($halaman > 1) ? ($halaman * $limit) - $limit : 0;

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query dasar dengan filter search
$query_base = "FROM t_project WHERE name LIKE '%$search%' OR item_desc LIKE '%$search%'";

// Hitung total data untuk pagination
$total_res = mysqli_query($conn, "SELECT COUNT(*) AS total $query_base");
$total_data = mysqli_fetch_assoc($total_res)['total'];
$total_halaman = ceil($total_data / $limit);

// Ambil data dengan LIMIT
$res = mysqli_query($conn, "SELECT * $query_base ORDER BY id DESC LIMIT $offset, $limit");

// --- LOGIKA PROSES CRUD ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btn_simpan'])) {
        $id_proyek = mysqli_real_escape_string($conn, $_POST['id_proyek']);
        $nama = mysqli_real_escape_string($conn, $_POST['name']);
        $no_pro = mysqli_real_escape_string($conn, $_POST['no_pro']);
        $items = isset($_POST['item_desc']) ? implode("\n", $_POST['item_desc']) : "";
        $items = mysqli_real_escape_string($conn, $items);
        mysqli_query($conn, "INSERT INTO t_project (id_proyek, name, no_pro, item_desc) VALUES ('$id_proyek', '$nama', '$no_pro', '$items')");
        header("Location: proyek.php?msg=success"); exit;
    }
    if (isset($_POST['btn_update'])) {
        $id = $_POST['id'];
        $id_proyek = mysqli_real_escape_string($conn, $_POST['id_proyek']);
        $nama = mysqli_real_escape_string($conn, $_POST['name']);
        $no_pro = mysqli_real_escape_string($conn, $_POST['no_pro']);
        $items = isset($_POST['item_desc']) ? implode("\n", $_POST['item_desc']) : "";
        $items = mysqli_real_escape_string($conn, $items);
        mysqli_query($conn, "UPDATE t_project SET name = '$nama', item_desc = '$items' WHERE id = '$id'");
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

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Proyek</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-1"></i> Tambah Proyek
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <form action="" method="GET" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari proyek atau item..." value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                <?php if($search): ?>
                    <a href="proyek.php" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NO</th>
                        <th>ID PROYEK</th>
                        <th>NAMA PROYEK</th>
                        <th>NO PRO</th>
                        <th>DESKRIPSI ITEM</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $list_proyek = []; 
                    $no = $offset + 1;
                    if(mysqli_num_rows($res) > 0):
                        while ($row = mysqli_fetch_assoc($res)):
                            $list_proyek[] = $row;
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['id_proyek']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['no_pro']); ?></td>
                        <td>
                            <?php 
                                $display_items = explode("\n", $row['item_desc']);
                                foreach($display_items as $i) {
                                    if(!empty(trim($i))) echo "• ".htmlspecialchars($i)."<br>";
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="proyek.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus proyek?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4" class="text-center py-4">Data tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($halaman <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?halaman=<?= $halaman - 1; ?>&search=<?= $search; ?>">Previous</a>
            </li>
            <?php for($i=1; $i<=$total_halaman; $i++): ?>
                <li class="page-item <?= ($halaman == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?halaman=<?= $i; ?>&search=<?= $search; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?halaman=<?= $halaman + 1; ?>&search=<?= $search; ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="proyek.php" method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Proyek</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID Proyek</label>
                    <input type="text" name="id_proyek" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Proyek</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">No PRO</label>
                    <input type="text" name="no_pro" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold d-flex justify-content-between">
                        Deskripsi Item
                        <button type="button" class="btn btn-sm btn-success add-item-btn">+ Item</button>
                    </label>
                    <div class="item-container">
                        <div class="input-group mb-2">
                            <input type="text" name="item_desc[]" class="form-control" placeholder="Isi deskripsi..." required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btn_simpan" class="btn btn-primary px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($list_proyek as $p): ?>
<div class="modal fade" id="editModal<?= $p['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="proyek.php" method="POST" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Edit Proyek #<?= $p['id']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $p['id']; ?>">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Proyek</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold d-flex justify-content-between">
                        Deskripsi Item
                        <button type="button" class="btn btn-sm btn-success add-item-btn">+ Item</button>
                    </label>
                    <div class="item-container">
                        <?php 
                        $current_items = explode("\n", $p['item_desc']);
                        foreach ($current_items as $index => $val): 
                        ?>
                        <div class="input-group mb-2">
                            <input type="text" name="item_desc[]" class="form-control" value="<?= htmlspecialchars($val); ?>" required>
                            <button type="button" class="btn btn-danger remove-item">Hapus</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btn_update" class="btn btn-success px-4">Update</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<script>
// Logic Tambah/Hapus baris input (Global listener)
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('add-item-btn')) {
        const modalBody = e.target.closest('.modal-body');
        const container = modalBody.querySelector('.item-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `<input type="text" name="item_desc[]" class="form-control" placeholder="Isi deskripsi..." required><button type="button" class="btn btn-danger remove-item">Hapus</button>`;
        container.appendChild(div);
    }
    if (e.target && e.target.classList.contains('remove-item')) {
        const container = e.target.closest('.item-container');
        if(container.querySelectorAll('.input-group').length > 1) {
            e.target.parentElement.remove();
        } else {
            alert('Minimal harus ada satu deskripsi.');
        }
    }
});
</script>

<?php include "footer.php"; ?>