<?php

include "koneksi.php";
include "header.php"; // Memanggil Sidebar dan CSS
$id_coating = mysqli_real_escape_string($conn, $_GET['id_coating']); 

// Query diperbarui dengan JOIN ke t_subkon
$query_header = "SELECT h.*, p.name as nama_proyek, s.nama as nama_subkon 
                 FROM t_coating_header h 
                 JOIN t_project p ON h.id_project = p.id 
                 JOIN t_subkon s ON h.id_subkon = s.id
                 WHERE h.id = '$id_coating'";
$header = mysqli_fetch_assoc(mysqli_query($conn, $query_header));

$name = $_SESSION['user_name'];
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
            <form action="proses_detail.php" method="POST" class="row g-3">
                <input type="hidden" name="id_coating" value="<?= $id_coating; ?>">
                
                <div class="col-md-3">
                    <label class="form-label fw-bold text-uppercase text-muted">Part Description</label>
                    <input type="text" name="part_desc" class="form-control" placeholder="Description..." required autofocus>
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

                <div class="col-md-12 d-flex justify-content-between align-items-center mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="visual_check" name="visual_check" value="ACC" checked>
                        <label class="form-check-label fw-bold" for="visual_check">Visual Check (ACC/NG)</label>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Item
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
                        <th class="text-start">Part Description</th>
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
                    $details = mysqli_query($conn, "SELECT * FROM t_coating_detail WHERE id_coating = $id_coating ORDER BY id DESC");
                    $no = 1;
                    while($d = mysqli_fetch_assoc($details)) {
                        // 1. Perbaiki pengecekan warna (dari PASS ke ACC)
                        $resColor = ($d['result'] == 'ACC') ? 'success' : 'danger';

                        // 2. Perbaiki icon visual check agar dinamis (Hijau jika ACC, Merah jika NG)
                        $visualIcon = ($d['visual_check'] == 'ACC') 
                                    ? "<i class='fas fa-check-circle text-success' title='ACC'></i>" 
                                    : "<i class='fas fa-times-circle text-danger' title='NG'></i>";

                        echo "<tr>
                                <td class='ps-4'>{$no}</td>
                                <td class='text-start fw-bold'>{$d['part_desc']}</td>
                                <td>{$d['t_1']}</td><td>{$d['t_2']}</td><td>{$d['t_3']}</td><td>{$d['t_4']}</td><td>{$d['t_5']}</td>
                                <td class='fw-bold bg-light'>{$d['avg']}</td>
                                <td>{$visualIcon}</td>
                                <td>{$d['qty']}</td>
                                <td><span class='badge bg-{$resColor}'>{$d['result']}</span></td>
                                <td>{$d['created_at']}</td>
                                <td>{$d['inspector']}</td>
                                <td>
                                    <a href='hapus_detail.php?id={$d['id']}&id_coating={$id_coating}' class='btn btn-sm btn-link text-danger' onclick='return confirm(\"Hapus item?\")'>
                                        <i class='fas fa-trash'></i>
                                    </a>
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


<?php include "footer.php"; // Memanggil Penutup Tag dan JS ?>