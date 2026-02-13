<?php

include "koneksi.php";
include "header.php"; // Memanggil Sidebar dan CSS
$id_coating = mysqli_real_escape_string($conn, $_GET['id_coating']); 

// Query diperbarui dengan JOIN ke t_subkon
$query_header = "SELECT h.*, p.name as nama_proyek, p.item_desc as item_desc, s.nama as nama_subkon, p.no_pro as no_pro, p.qty as qty
                 FROM t_coating_header h 
                 JOIN t_project p ON h.id_project = p.id 
                 JOIN t_subkon s ON h.id_subkon = s.id
                 WHERE h.id = '$id_coating'";
$header = mysqli_fetch_assoc(mysqli_query($conn, $query_header));

$name = $_SESSION['user_name'];

// Pecah item_desc dari database menjadi array untuk pilihan datalist
$pilihan_item = [];
if (!empty($header['item_desc'])) {
    // Memecah berdasarkan baris baru dan membersihkan spasi kosong
    $pilihan_item = array_filter(explode("\n", $header['item_desc']));
}

?>

    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; border-radius: 12px; }
        .table thead { background-color: #4e73df; color: white; font-size: 0.9rem; }
        .form-label { font-size: 0.85rem; margin-bottom: 0.3rem; }
    </style>

<div class="container-fluid py-4 px-5">
    <div class="card shadow-sm mb-4 border-start border-primary border-5">
    <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="row g-3">
                    <div class="col-auto border-end pe-4">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Nomor PRO</span>
                        <h5 class="mb-1"><?= $header['no_pro']; ?></h5>
                        <p class="mb-0 text-muted small">QTY: <strong><?= $header['qty']; ?></strong></p>
                    </div>
                    
                    <div class="col-auto border-end pe-4">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Project Information</span>
                        <h5 class="mb-1"><i class="fas fa-building me-2 text-primary"></i><?= $header['nama_proyek']; ?></h5>
                        <p class="mb-0 text-muted small"><i class="fas fa-id-badge me-2"></i>Subkon: <strong><?= $header['nama_subkon']; ?></strong></p>
                    </div>

                    <div class="col-auto ps-4">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Inspection Identity</span>
                        <h3 class="mb-1 text-dark">
                            <i class="fas fa-file-contract me-2 text-primary"></i><?= $header['report_no']; ?>
                        </h3>
                        <span class="badge bg-info text-dark">
                            <i class="fas fa-drawing-rolled me-1"></i> Dwg No: <?= $header['drawing_no']; ?>
                        </span>
                    </div>
                </div>

                <div>
                    <a href="read.php" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-chevron-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-secondary">Thickness Coating</h5>
        </div>
        <div class="card-body">
            <form action="proses_detail.php" method="POST" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="id_coating" value="<?= $id_coating; ?>">
                <div class="row mt-3">
                    <div class="col-md-6">
                        <select name="progress_ke" class="form-select" aria-label="Default select example">
                        <option selected>-- Pengecekan ke : --</option>
                        <option value="0">0 %</option>
                        <option value="30">30 %</option>
                        <option value="50">50 %</option>
                        <option value="80">80 %</option>
                        <option value="100">100 %</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-uppercase text-muted">Part Description</label>
                    <input type="text" name="part_desc" id="part_desc" class="form-control" 
                        placeholder="Pilih atau ketik manual..." 
                        list="item_list" required autofocus autocomplete="off">
                    
                    <datalist id="item_list">
                        <?php foreach ($pilihan_item as $item): ?>
                            <option value="<?= htmlspecialchars(trim($item)); ?>">
                        <?php endforeach; ?>
                    </datalist>
                    <small class="text-muted" style="font-size: 0.7rem;">*Klik 2x untuk melihat daftar item proyek</small>
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="">(min) µ</label>
                    <input type="number" name="min" class="form-control">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold">T1</label>
                    <input type="number" step="0.01" name="t_1" class="form-control t-input" placeholder="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold">T2</label>
                    <input type="number" step="0.01" name="t_2" class="form-control t-input" placeholder="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold">T3</label>
                    <input type="number" step="0.01" name="t_3" class="form-control t-input" placeholder="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold">T4</label>
                    <input type="number" step="0.01" name="t_4" class="form-control t-input" placeholder="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold">T5</label>
                    <input type="number" step="0.01" name="t_5" class="form-control t-input" placeholder="0">
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold text-primary">Average</label>
                    <input type="number" step="0.01" name="avg" id="avg_display" class="form-control bg-light fw-bold" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold">QTY</label>
                    <input type="number" name="qty" class="form-control" value="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-uppercase text-muted">Result</label>
                    <select name="result" id="result_dropdown" class="form-select fw-bold">
                        <option value="ACC">ACC</option>
                        <option value="NG">NG</option>
                    </select>
                </div>

                <input hidden type="text" name="inspector" value="<?= $name; ?>">
                <!-- upload -->
                <div class="col-md-10">
                    <label class="form-label fw-bold text-uppercase text-muted">Lampiran Foto</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-camera"></i></span>
                        <input type="file" name="attachments[]" class="form-control" multiple accept="image/*,.pdf">
                    </div>
                    <div class="form-text text-danger small">* Pilih beberapa file sekaligus (Dokumen Bisa > 1)</div>
                    
                    <button type="button" class="btn btn-outline-danger btn-sm mt-3 fw-bold" id="add-ng-detail">
                        <i class="fas fa-exclamation-triangle me-1"></i> Tambah Detail Kerusakan (NG)
                    </button>
                </div>

                <div class="col-md-12 mt-2" id="ng-container">
                    </div>

                <div class="col-md-12 d-flex justify-content-between align-items-center mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="visual_check" name="visual_check" value="ACC" checked>
                        <label class="form-check-label fw-bold" for="visual_check">Visual Check (ACC/NG)</label>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i> Simpan Inspeksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm text-nowrap">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Progress</th>
                        <th class="text-start">Part Description</th>
                        <th>Min</th>
                        <th>T1</th><th>T2</th><th>T3</th><th>T4</th><th>T5</th>
                        <th class="bg-light">AVG</th>
                        <th>Visual</th>
                        <th>QTY</th>
                        <th>Result</th>
                        <th>Tgl/Jam</th>
                        <th>Inspektor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $details = mysqli_query($conn, "SELECT * FROM t_coating_detail WHERE id_coating = $id_coating ORDER BY progress_ke ASC");
                    $no = 1;
                    while($d = mysqli_fetch_assoc($details)) {
                        // 1. Perbaiki pengecekan warna (dari PASS ke ACC)
                        $resColor = ($d['result'] == 'ACC') ? 'success' : 'danger';

                        // 2. Perbaiki icon visual check agar dinamis (Hijau jika ACC, Merah jika NG)
                        $visualIcon = ($d['visual_check'] == 'ACC') 
                                    ? "<i class='fas fa-check-circle text-success' title='ACC'></i>" 
                                    : "<i class='fas fa-times-circle text-danger' title='NG'></i>";
                        // Pecah string foto menjadi array
                        $foto_list = !empty($d['foto']) ? explode(',', $d['foto']) : [];
                        $modalId = "modalFoto" . $d['id']; // ID unik untuk setiap baris

                        $modalNgId = "modalNg" . $d['id']; // ID unik untuk modal NG

                        // Query untuk mengambil data NG dari tabel relasi
                        $id_det = $d['id'];
                        $q_ng = mysqli_query($conn, "SELECT * FROM t_coating_ng WHERE id_detail = '$id_det'");
                        $has_ng = mysqli_num_rows($q_ng) > 0;

                        echo "<tr>
                                <td class='ps-4'>{$no}</td>
                                <td class='text-start fw-bold'>{$d['progress_ke']} %</td>
                                <td class='text-start fw-bold'>{$d['part_desc']}</td>
                                <td class='text-start fw-bold'>{$d['min']}</td>
                                <td>{$d['t_1']}</td><td>{$d['t_2']}</td><td>{$d['t_3']}</td><td>{$d['t_4']}</td><td>{$d['t_5']}</td>
                                <td class='fw-bold bg-light'>{$d['avg']}</td>
                                <td class='text-center'>{$visualIcon}</td>
                                <td class='text-center'>{$d['qty']}</td>
                                <td class='text-center'><span class='badge bg-{$resColor}'>{$d['result']}</span></td>
                                <td class='text-center'>{$d['created_at']}</td>
                                <td>{$d['inspector']}</td>
                                <td>
                                    <div class='d-flex justify-content-center'>
                                        <button type='button' class='btn btn-sm btn-link text-warning me-1' data-bs-toggle='modal' data-bs-target='#{$modalNgId}' title='Detail NG'>
                                            <i class='fas fa-exclamation-triangle'></i>
                                        </button>

                                        <button type='button' class='btn btn-sm btn-link text-info me-1' data-bs-toggle='modal' data-bs-target='#{$modalId}' title='Lihat Foto'>
                                            <i class='fas fa-camera'></i>
                                        </button>

                                        <a href='hapus_detail.php?id={$d['id']}&id_coating={$id_coating}' class='btn btn-sm btn-link text-danger' onclick='return confirm(\"Hapus item?\")'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </div>

                                    <div class='modal fade' id='{$modalId}' tabindex='-1' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title text-dark'>Lampiran Foto: {$d['part_desc']}</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class='modal-body text-center'>";
                                                    if (!empty($foto_list)) {
                                                        foreach ($foto_list as $img) {
                                                            echo "<div class='card mb-3 shadow-sm'>
                                                                    <img src='uploads/{$img}' class='img-fluid rounded-top'>
                                                                    <div class='card-footer py-1 small'>{$img}</div>
                                                                </div>";
                                                        }
                                                    } else {
                                                        echo "<p class='text-muted py-4'>Tidak ada foto.</p>";
                                                    }
                                                echo "</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class='modal fade' id='{$modalNgId}' tabindex='-1' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content text-start'>
                                                <div class='modal-header bg-warning'>
                                                    <h5 class='modal-title fw-bold'>Detail Kerusakan (NG)</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class='modal-body'>";
                                                    if ($has_ng) {
                                                        echo "<div class='list-group'>";
                                                        while($ng = mysqli_fetch_assoc($q_ng)) {
                                                            echo "<div class='list-group-item'>
                                                                    <h6 class='mb-1 fw-bold text-danger'>{$ng['ng_type']}</h6>
                                                                    <p class='mb-0 small text-muted'>{$ng['ng_remark']}</p>
                                                                </div>";
                                                        }
                                                        echo "</div>";
                                                    } else {
                                                        echo "<div class='text-center py-4'>
                                                                <i class='fas fa-check-circle fa-3x text-success mb-2 opacity-50'></i>
                                                                <p class='mb-0'>Item ini dinyatakan <strong>Visual ACC</strong>.</p>
                                                            </div>";
                                                    }
                                                echo "</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
// Script Hitung Otomatis Average
const tInputs = document.querySelectorAll('.t-input');
const avgDisplay = document.getElementById('avg_display');

tInputs.forEach(input => {
    input.addEventListener('input', () => {
        let total = 0;
        let count = 0;
        tInputs.forEach(ti => {
            if(ti.value !== "") {
                total += parseFloat(ti.value);
                count++;
            }
        });
        const average = count > 0 ? (total / count).toFixed(2) : 0;
        avgDisplay.value = average;
    });
});


document.getElementById('visual_check').addEventListener('change', function() {
    const resultDropdown = document.getElementById('result_dropdown');
    if(!this.checked) {
        resultDropdown.value = "NG"; // Jika uncheck, otomatis set NG
    } else {
        resultDropdown.value = "ACC"; // Jika check, balik ke ACC
    }
});
</script>


<?php include "footer.php"; ?>