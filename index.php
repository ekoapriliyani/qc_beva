<?php 
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inspeksi QC | Maroon Edition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #800000; /* Maroon */
            --secondary-color: #a52a2a;
            --accent-color: #ffd700; /* Gold untuk kontras kecil */
            --bg-light: #f8f9fa;
            --text-dark: #333;
            --text-white: #ffffff;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --card-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
        }

        body {
            display: flex;
            background-color: var(--bg-light);
            height: 100vh;
            overflow: hidden;
        }

        /* --- SIDEBAR --- */
        #sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, #4d0000 100%);
            color: var(--text-white);
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        #sidebar.collapsed { width: var(--sidebar-collapsed-width); }

        .sidebar-header {
            padding: 25px 20px;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-menu { list-style: none; padding: 20px 0; flex-grow: 1; }

        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            margin: 4px 10px;
            border-radius: 8px;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .menu-item i { width: 25px; font-size: 1.1rem; }

        .submenu {
            background-color: rgba(0,0,0,0.2);
            margin: 0 10px;
            border-radius: 8px;
            overflow: hidden;
        }

        .submenu-item {
            padding: 10px 25px 10px 50px;
            display: block;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: 0.3s;
        }

        .submenu-item:hover { color: var(--accent-color); }

        .collapsed .menu-text, .collapsed .sidebar-header span { display: none; }

        /* --- MAIN CONTENT --- */
        #main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        header {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header-left { display: flex; align-items: center; gap: 20px; }
        
        #toggle-btn {
            background: #f0f0f0; border: none;
            width: 40px; height: 40px; border-radius: 8px;
            cursor: pointer; color: var(--primary-color);
        }

        /* --- DASHBOARD GRID --- */
        .content-area { padding: 25px; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 20px;
            border-left: 5px solid var(--primary-color);
        }

        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 10px;
            background: rgba(128, 0, 0, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-info h3 { font-size: 0.9rem; color: #666; margin-bottom: 5px; }
        .stat-info p { font-size: 1.5rem; font-weight: bold; color: var(--text-dark); }

        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th {
            text-align: left;
            padding: 12px;
            background: #f8f9fa;
            color: #666;
            font-size: 0.85rem;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .bg-success { background: #d4edda; color: #155724; }
        .bg-danger { background: #f8d7da; color: #721c24; }

        @media (max-width: 992px) {
            .charts-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-microscope"></i> <span>QC INSPECTOR</span>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="fas fa-chart-line"></i>
                <span class="menu-text">Overview</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-file-import"></i>
                <span class="menu-text">Incoming Inspection</span>
            </a>
            
            <div>
                <div class="menu-item">
                    <i class="fas fa-industry"></i>
                    <span class="menu-text">In-Process QC</span>
                </div>
                <div class="submenu">
                    <a href="wiremesh/index.php" class="submenu-item">Wiremesh (WM)</a>
                    <a href="#" class="submenu-item">Bronjong</a>
                </div>
            </div>

            <a href="#" class="menu-item">
                <i class="fas fa-clipboard-check"></i>
                <span class="menu-text">Outgoing / Final</span>
            </a>

            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span class="menu-text">Settings</span>
            </a>
        </div>
    </nav>

    <div id="main-content">
        <header>
            <div class="header-left">
                <button id="toggle-btn"><i class="fas fa-bars"></i></button>
                <h2 style="color: var(--primary-color);">Dashboard Monitoring QC - PT Bevananda Mustika</h2>
            </div>
            <div class="header-right">
                <span style="font-size: 0.9rem; color: #666;"><i class="far fa-calendar-alt"></i> 23 Jan 2026</span>
            </div>
        </header>

        <main class="content-area">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-box"></i></div>
                    <div class="stat-info">
                        <h3>Total Inspeksi (Hari Ini)</h3>
                        <p>142</p>
                    </div>
                </div>
                <div class="stat-card" style="border-left-color: #28a745;">
                    <div class="stat-icon" style="color: #28a745; background: rgba(40,167,69,0.1);"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3>Passed</h3>
                        <p>138</p>
                    </div>
                </div>
                <div class="stat-card" style="border-left-color: #dc3545;">
                    <div class="stat-icon" style="color: #dc3545; background: rgba(220,53,69,0.1);"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <h3>Rejected</h3>
                        <p>4</p>
                    </div>
                </div>
                <div class="stat-card" style="border-left-color: var(--accent-color);">
                    <div class="stat-icon" style="color: #b8860b; background: rgba(255,215,0,0.1);"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3>Pending</h3>
                        <p>12</p>
                    </div>
                </div>
            </div>

            <div class="charts-container">
                <div class="card">
                    <div class="card-header">
                        <h4 style="color: var(--primary-color);">Tren Kualitas Mingguan</h4>
                        <i class="fas fa-ellipsis-v"></i>
                    </div>
                    <canvas id="weeklyChart" height="150"></canvas>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 style="color: var(--primary-color);">Inspeksi Terakhir</h4>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Wiremesh M6</td>
                                <td><span class="badge bg-success">PASS</span></td>
                            </tr>
                            <tr>
                                <td>Bronjong 2x1x0.5</td>
                                <td><span class="badge bg-success">PASS</span></td>
                            </tr>
                            <tr>
                                <td>Wiremesh M8</td>
                                <td><span class="badge bg-danger">FAIL</span></td>
                            </tr>
                            <tr>
                                <td>Baja Tulangan 10mm</td>
                                <td><span class="badge bg-success">PASS</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        // Data Dummy untuk Chart
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                datasets: [{
                    label: 'Produk Lulus (OK)',
                    data: [65, 78, 72, 85, 80, 92],
                    borderColor: '#800000',
                    backgroundColor: 'rgba(128, 0, 0, 0.1)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Produk Reject (NG)',
                    data: [5, 3, 8, 2, 4, 1],
                    borderColor: '#dc3545',
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>