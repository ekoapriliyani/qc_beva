<?php
include "koneksi.php";
include "header.php"; // Ini sudah mengecek login, memuat CSS, Navigasi, dan Sidebar

// Ambil data statistik
$count_p = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM t_project"))[0];
$count_s = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM t_subkon"))[0];
$count_c = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM t_coating_header"))[0];
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white p-3" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small fw-bold opacity-75">Total Proyek</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $count_p; ?></h2>
                </div>
                <i class="fas fa-project-diagram fa-3x opacity-25"></i>
            </div>
            <a href="proyek.php" class="text-white small mt-3 text-decoration-none">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
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
            <a href="subkon.php" class="text-white small mt-3 text-decoration-none">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
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
            <a href="read.php" class="text-white small mt-3 text-decoration-none">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 text-center py-5">
        <img src="https://illustrations.popsy.co/blue/data-analysis.svg" alt="Dashboard" style="max-width: 300px;" class="mb-4">
        <h2 class="fw-bold text-dark">System Monitoring Coating</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Selamat datang, <strong><?= $_SESSION['user_email']; ?></strong>. 
            Gunakan menu di samping untuk mengelola data proyek, memantau kinerja subkon, atau membuat laporan inspeksi baru.
        </p>
    </div>
</div>

<?php 
include "footer.php"; // Memanggil penutup div dan script JS
?>