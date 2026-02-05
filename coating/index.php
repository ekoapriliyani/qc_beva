<?php
include "koneksi.php";
include "header.php"; 

// 1. Ambil data statistik ringkas
$count_p = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM t_project"))[0];
$count_s = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM t_subkon"))[0];
$count_c = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM t_coating_header"))[0];

// 2. Query Analisa Subkon (ACC vs NG)
$sql_analisa = "SELECT 
                    s.nama AS nama_subkon,
                    SUM(CASE WHEN d.result = 'ACC' THEN 1 ELSE 0 END) AS total_acc,
                    SUM(CASE WHEN d.result = 'NG' THEN 1 ELSE 0 END) AS total_ng,
                    COUNT(d.id) AS total_item
                FROM t_subkon s
                LEFT JOIN t_coating_header h ON s.id = h.id_subkon
                LEFT JOIN t_coating_detail d ON h.id = d.id_coating
                GROUP BY s.id
                ORDER BY total_ng DESC"; // Urutkan dari NG terbanyak untuk analisa risiko
$res_analisa = mysqli_query($conn, $sql_analisa);
?>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white p-3" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small fw-bold opacity-75">Total Proyek</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_p; ?></h2>
                </div>
                <i class="fas fa-project-diagram fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white p-3" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small fw-bold opacity-75">Total Subkon</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_s; ?></h2>
                </div>
                <i class="fas fa-users-cog fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white p-3" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small fw-bold opacity-75">Laporan Coating</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_c; ?></h2>
                </div>
                <i class="fas fa-file-medical-alt fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-bar text-warning me-2"></i>Penilaian Kinerja Subkon (ACC vs NG)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">NAMA SUBKON</th>
                                <th class="text-center">TOTAL ITEM</th>
                                <th class="text-center">ACC</th>
                                <th class="text-center">NG</th>
                                <th>PRESENTASE ACC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($res_analisa)): 
                                $total = $row['total_item'];
                                $persen_acc = ($total > 0) ? round(($row['total_acc'] / $total) * 100, 1) : 0;
                                $bar_color = ($persen_acc >= 80) ? 'bg-success' : (($persen_acc >= 50) ? 'bg-warning' : 'bg-danger');
                            ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= $row['nama_subkon']; ?></td>
                                <td class="text-center"><?= $total; ?></td>
                                <td class="text-center text-success fw-bold"><?= $row['total_acc']; ?></td>
                                <td class="text-center text-danger fw-bold"><?= $row['total_ng']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar <?= $bar_color; ?>" style="width: <?= $persen_acc; ?>%"></div>
                                        </div>
                                        <span class="small fw-bold"><?= $persen_acc; ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 bg-dark text-white p-4" style="border-radius: 15px; height: 100%;">
            <h5 class="fw-bold"><i class="fas fa-lightbulb text-warning me-2"></i>Insight Analisa</h5>
            <p class="small opacity-75 mt-3">Subkon dengan jumlah **NG (Not Good)** yang tinggi memerlukan evaluasi proses coating atau penggantian material. </p>
            <ul class="small opacity-75">
                <li><span class="badge bg-success me-1">Good</span> ≥ 80% ACC</li>
                <li><span class="badge bg-warning me-1">Warning</span> 50% - 79% ACC</li>
                <li><span class="badge bg-danger me-1">Critical</span> < 50% ACC</li>
            </ul>
            <div class="mt-auto">
                <img src="https://illustrations.popsy.co/blue/manager.svg" alt="Analysis" style="width: 100%; opacity: 0.6;">
            </div>
        </div>
    </div>
</div>

<?php 
include "footer.php"; 
?>