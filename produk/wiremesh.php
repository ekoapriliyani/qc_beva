<?php
include "koneksi.php";

// --- KONFIGURASI PAGINATION ---
$limit = 10; 
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($page > 1) ? ($page * $limit) - $limit : 0;

// --- LOGIKA SEARCHING ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE prod_code LIKE '%$search%' OR d_kawat LIKE '%$search%'";
}

// --- HITUNG TOTAL DATA ---
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM t_produk $where_clause");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

// --- LOGIKA PROSES (CRUD) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tambah Data
    if (isset($_POST['btn_simpan'])) {
        $prod_code = mysqli_real_escape_string($conn, $_POST['prod_code']);
        $d_kawat   = mysqli_real_escape_string($conn, $_POST['d_kawat']);
        $tol_min   = mysqli_real_escape_string($conn, $_POST['tol_min']);
        $tol_plus  = mysqli_real_escape_string($conn, $_POST['tol_plus']);
        $p_produk  = mysqli_real_escape_string($conn, $_POST['p_produk']);
        $l_produk  = mysqli_real_escape_string($conn, $_POST['l_produk']);
        $p_mesh    = mysqli_real_escape_string($conn, $_POST['p_mesh']);
        $l_mesh    = mysqli_real_escape_string($conn, $_POST['l_mesh']);
        $tol       = mysqli_real_escape_string($conn, $_POST['tol']);

        mysqli_query($conn, "INSERT INTO t_produk (prod_code, d_kawat, tol_min, tol_plus, p_produk, l_produk, p_mesh, l_mesh, tol) 
                             VALUES ('$prod_code', '$d_kawat', '$tol_min', '$tol_plus', '$p_produk', '$l_produk', '$p_mesh', '$l_mesh', '$tol')");
        header("Location: wiremesh.php?msg=success"); exit;
    }

    // Update Data
    if (isset($_POST['btn_update'])) {
        $id        = $_POST['id'];
        $prod_code = mysqli_real_escape_string($conn, $_POST['prod_code']);
        $d_kawat   = mysqli_real_escape_string($conn, $_POST['d_kawat']);
        $tol_min   = mysqli_real_escape_string($conn, $_POST['tol_min']);
        $tol_plus  = mysqli_real_escape_string($conn, $_POST['tol_plus']);
        $p_produk  = mysqli_real_escape_string($conn, $_POST['p_produk']);
        $l_produk  = mysqli_real_escape_string($conn, $_POST['l_produk']);
        $p_mesh    = mysqli_real_escape_string($conn, $_POST['p_mesh']);
        $l_mesh    = mysqli_real_escape_string($conn, $_POST['l_mesh']);
        $tol       = mysqli_real_escape_string($conn, $_POST['tol']);

        mysqli_query($conn, "UPDATE t_produk SET 
            prod_code = '$prod_code', d_kawat = '$d_kawat', tol_min = '$tol_min', 
            tol_plus = '$tol_plus', p_produk = '$p_produk', l_produk = '$l_produk', 
            p_mesh = '$p_mesh', l_mesh = '$l_mesh', tol = '$tol' 
            WHERE id = '$id'");
        header("Location: wiremesh.php?msg=updated"); exit;
    }
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM t_produk WHERE id = '$id'");
    header("Location: wiremesh.php?msg=deleted"); exit;
}

include "header.php";
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Produk Wiremesh</h2>
            <p class="text-muted">Total: <?= $total_data ?> data ditemukan</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-1"></i> Tambah Wiremesh
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <form action="wiremesh.php" method="GET" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kode Produk..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-secondary" type="submit">Cari</button>
                <?php if(!empty($search)): ?>
                    <a href="wiremesh.php" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            Data berhasil <strong><?= ($_GET['msg'] == 'success') ? 'disimpan' : (($_GET['msg'] == 'updated') ? 'diperbarui' : 'dihapus'); ?></strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">NO</th>
                        <th>Prod Code</th>
                        <th>D Kawat</th>
                        <th>Tol Min</th>
                        <th>Tol Max</th>
                        <th>P Prod</th>
                        <th>L Prod</th>
                        <th>P Mesh</th>
                        <th>L Mesh</th>
                        <th>Tol Mesh</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM t_produk $where_clause ORDER BY id DESC LIMIT $offset, $limit");
                    $list_wm = []; 
                    $no = $offset + 1;
                    while ($row = mysqli_fetch_assoc($res)):
                        $list_wm[] = $row; 
                    ?>
                    <tr>
                        <td class="ps-4 text-muted"><?= $no++; ?></td>
                        <td><?= $row['prod_code']; ?></td>
                        <td><?= $row['d_kawat']; ?></td>
                        <td><?= $row['tol_min']; ?></td>
                        <td><?= $row['tol_plus']; ?></td>
                        <td><?= $row['p_produk']; ?></td>
                        <td><?= $row['l_produk']; ?></td>
                        <td><?= $row['p_mesh']; ?></td>
                        <td><?= $row['l_mesh']; ?></td>
                        <td><?= $row['tol']; ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-success me-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="wiremesh.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($total_pages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?halaman=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="wiremesh.php" method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Wiremesh</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="small fw-bold">Prod Code</label>
                    <input type="text" name="prod_code" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-2"><label class="small fw-bold">D Kawat</label><input type="text" name="d_kawat" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">Toleransi Mesh</label><input type="text" name="tol" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">Tol Min</label><input type="text" name="tol_min" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">Tol Plus</label><input type="text" name="tol_plus" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">P Produk</label><input type="text" name="p_produk" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">L Produk</label><input type="text" name="l_produk" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">P Mesh</label><input type="text" name="p_mesh" class="form-control" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">L Mesh</label><input type="text" name="l_mesh" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php foreach ($list_wm as $s): ?>
<div class="modal fade" id="editModal<?= $s['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="wiremesh.php" method="POST" class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Edit Wiremesh #<?= $s['id']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" value="<?= $s['id']; ?>">
                <div class="mb-2">
                    <label class="small fw-bold">Prod Code</label>
                    <input type="text" name="prod_code" class="form-control" value="<?= $s['prod_code']; ?>" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-2"><label class="small fw-bold">D Kawat</label><input type="text" name="d_kawat" class="form-control" value="<?= $s['d_kawat']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">Toleransi Mesh</label><input type="text" name="tol" class="form-control" value="<?= $s['tol']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">Tol Min</label><input type="text" name="tol_min" class="form-control" value="<?= $s['tol_min']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">Tol Plus</label><input type="text" name="tol_plus" class="form-control" value="<?= $s['tol_plus']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">P Produk</label><input type="text" name="p_produk" class="form-control" value="<?= $s['p_produk']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">L Produk</label><input type="text" name="l_produk" class="form-control" value="<?= $s['l_produk']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">P Mesh</label><input type="text" name="p_mesh" class="form-control" value="<?= $s['p_mesh']; ?>" required></div>
                    <div class="col-6 mb-2"><label class="small fw-bold">L Mesh</label><input type="text" name="l_mesh" class="form-control" value="<?= $s['l_mesh']; ?>" required></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btn_update" class="btn btn-success text-white">Update Data</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?php include "footer.php"; ?>