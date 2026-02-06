<?php
session_start();
// Proteksi halaman: jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Logika untuk menandai menu yang sedang aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QC Coating System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <style>
        body { overflow-x: hidden; background-color: #f8f9fc; }
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            transition: margin .25s ease-out;
            display: flex;
            flex-direction: column;
        }
        #sidebar-wrapper .sidebar-heading { 
            padding: 1.5rem 1.25rem; 
            font-size: 1.2rem; 
            font-weight: bold; 
            color: white; 
            text-align: center;
        }
        #sidebar-wrapper .list-group-item { 
            border: none; 
            padding: 1rem 1.5rem; 
            background: transparent; 
            color: rgba(255,255,255,.8); 
        }
        #sidebar-wrapper .list-group-item:hover { 
            background: rgba(255,255,255,.1); 
            color: white; 
        }
        #sidebar-wrapper .list-group-item.active { 
            color: white; 
            background: rgba(255,255,255,.15); 
            font-weight: bold;
            border-left: 4px solid #fff;
        }
        #wrapper.toggled #sidebar-wrapper { margin-left: -250px; }
        #page-content-wrapper { width: 100%; }
        .navbar { padding: 0.8rem 1.5rem; }
        /* Logout di bagian paling bawah */
        .sidebar-footer { margin-top: auto; }

        .modal-body img {
            border-radius: 8px;
            transition: transform 0.3s;
            cursor: zoom-in;
        }
        .modal-body img:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>

<div class="d-flex" id="wrapper">
    <div id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom border-light border-opacity-25">
            <i class="fas fa-paint-roller me-2"></i> QC PROJECT
        </div>
        
        <div class="list-group list-group-flush mt-3">
            <a href="index.php" class="list-group-item list-group-item-action <?= ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a href="proyek.php" class="list-group-item list-group-item-action <?= ($current_page == 'proyek.php') ? 'active' : ''; ?>">
                <i class="fas fa-project-diagram me-2"></i> Proyek
            </a>
            <a href="subkon.php" class="list-group-item list-group-item-action <?= ($current_page == 'subkon.php') ? 'active' : ''; ?>">
                <i class="fas fa-users-cog me-2"></i> Subkon
            </a>
            <a href="read.php" class="list-group-item list-group-item-action <?= ($current_page == 'read.php') ? 'active' : ''; ?>">
                <i class="fas fa-file-medical-alt me-2"></i> Inspeksi Coating
            </a>
            <a href="fabrication.php" class="list-group-item list-group-item-action <?= ($current_page == 'fabrication.php') ? 'active' : ''; ?>">
                <i class="fas fa-file-medical-alt me-2"></i> Inspeksi Fabrication
            </a>
            <a href="laporan_coating.php" class="list-group-item list-group-item-action <?= ($current_page == 'laporan_coating.php') ? 'active' : ''; ?>">
                <i class="fas fa-file-medical-alt me-2"></i> Laporan Coating
            </a>
        </div>

        <div class="sidebar-footer border-top border-light border-opacity-25">
            <a href="logout.php" class="list-group-item list-group-item-action text-warning" onclick="return confirm('Yakin ingin keluar?')">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
            <div class="container-fluid">
                <button class="btn btn-primary btn-sm" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto me-2 small text-muted">
                    <i class="far fa-user-circle me-1"></i> <?= $_SESSION['user_email']; ?>
                </div>
            </div> 
        </nav>
        
        <div class="container-fluid p-4">