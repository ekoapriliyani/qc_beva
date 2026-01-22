<?php 
require_once 'functions.php';

// Data statis untuk identitas form (karena sudah tidak pakai m_produk dinamis)
$nama_produk = "Bronjong / Gabions";
$kode_form   = "BM-F-QC-02-R01";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QC - <?= $nama_produk ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --maroon: #800000; --maroon-dark: #600000; }
        body { background-color: #f4f4f4; }
        .bg-maroon { background-color: var(--maroon); color: white; }
        .btn-maroon { background-color: var(--maroon); color: white; border: none; font-weight: bold; }
        .btn-maroon:hover { background-color: var(--maroon-dark); color: white; }
        .text-maroon { color: var(--maroon); }
        .card { border-radius: 8px; border: none; }
        .card-header-qc { border-top: 5px solid var(--maroon); background: white; }
        label { font-weight: bold; color: #444; font-size: 0.9rem; }
        .section-title { border-bottom: 2px solid #eee; padding-bottom: 5px; margin-bottom: 15px; font-size: 1rem; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<nav class="navbar bg-maroon shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">BEVANANDA QC SYSTEM</span>
    </div>
</nav>

<div class="container mb-5">
    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-qc d-flex justify-content-between align-items-center p-3">
            <h5 class="mb-0 text-maroon">FORM INSPEKSI QC HARIAN: <?= strtoupper($nama_produk) ?></h5>
            <span class="badge bg-secondary px-3 py-2"><?= $kode_form ?></span>
        </div>
        <div class="card-body p-4">
            <form action="proses_bronjong.php" method="POST">
                
                <div class="row bg-light p-3 mb-4 rounded border-start border-4 border-danger g-3">
                    <div class="col-md-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label>Shift</label>
                        <select name="shift" class="form-select">
                            <option value="1">Shift 1</option>
                            <option value="2">Shift 2</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>No. PRO</label>
                        <input type="text" name="no_pro" class="form-control" placeholder="Input No. PRO" required>
                    </div>
                    <div class="col-md-4">
                        <label>Operator</label>
                        <input type="text" name="operator" class="form-control" placeholder="Nama Operator" required>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="section-title text-maroon fw-bold">UKURAN AKTUAL (m)</h6>
                        <div class="mb-3">
                            <label class="small">Ukuran Standar (X180)</label>
                            <input type="text" name="ukuran" class="form-control" placeholder="Contoh: X180">
                        </div>
                        <div class="row g-2">
                            <div class="col">
                                <label class="small">Lebar 1</label>
                                <input type="number" step="0.01" name="lebar_1" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col">
                                <label class="small">Lebar 2</label>
                                <input type="number" step="0.01" name="lebar_2" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h6 class="section-title text-maroon fw-bold">MATERIAL & COATING</h6>
                        <div class="mb-3">
                            <label class="small">Jenis Galvanis</label>
                            <select name="jenis" class="form-select">
                                <option value="Heavy Galvanis">Heavy Galvanis</option>
                                <option value="Zinc + Alu">Zinc + Alu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small">Kelas Lapisan</label>
                            <select name="kelas" class="form-select">
                                <option value="Kelas 1">Kelas 1</option>
                                <option value="Kelas 2">Kelas 2</option>
                                <option value="Kelas 3">Kelas 3</option>
                            </select>
                        </div>
                        <div>
                            <label class="small">Tipe Plastik (Jika ada)</label>
                            <select name="tipe" class="form-select">
                                <option value="PVC">PVC</option>
                                <option value="HDPE">HDPE</option>
                                <option value="-">Tanpa Lapisan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h6 class="section-title text-maroon fw-bold">DIAMETER & LILITAN (mm)</h6>
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <label class="small">Ø Anyam</label>
                                <input type="number" step="0.01" name="d_anyam" class="form-control" placeholder="Anyam 1">
                            </div>
                            <div class="col">
                                <label class="small">Ø Frame</label>
                                <input type="number" step="0.01" name="d_frame" class="form-control" placeholder="Frame 1">
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="small">Ø Anyam 2</label>
                                <input type="number" step="0.01" name="d_anyam_2" class="form-control" placeholder="Anyam 2">
                            </div>
                            <div class="col">
                                <label class="small">Ø Frame 2</label>
                                <input type="number" step="0.01" name="d_frame_2" class="form-control" placeholder="Frame 2">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-7">
                                <label class="small">P. Lilitan (Max 40)</label>
                                <input type="number" step="0.01" name="p_lilitan" class="form-control">
                            </div>
                            <div class="col-5">
                                <label class="small">Jml Lilitan</label>
                                <input type="number" name="jml_lilitan" class="form-control" value="3">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label class="form-label">Status Hasil Inspeksi</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusOk" value="OK" checked>
                                <label class="form-check-label text-success" for="statusOk">PASSED (OK)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNg" value="NG">
                                <label class="form-check-label text-danger" for="statusNg">REJECT (NG)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="reset" class="btn btn-outline-secondary px-4 me-2">RESET</button>
                        <button type="submit" class="btn btn-maroon btn-lg px-5 shadow">SIMPAN DATA</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<footer class="text-center text-muted mb-4">
    <small>&copy; <?= date('Y') ?> IT Department - PT Bevananda Mustika</small>
</footer>

<script>
    // Menangkap parameter 'status' dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'success') {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data Inspeksi Bronjong telah disimpan ke database.',
            icon: 'success',
            confirmButtonColor: '#800000', // Warna Merah Hati
            timer: 3000,
            timerProgressBar: true
        });
    } else if (status === 'error') {
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data.',
            icon: 'error',
            confirmButtonColor: '#800000'
        });
    }
</script>

</body>
</html>