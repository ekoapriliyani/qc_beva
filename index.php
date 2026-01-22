<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inspeksi QC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #800000; /* Merah Hati / Maroon */
            --secondary-color: #a52a2a;
            --bg-light: #f4f4f4;
            --text-white: #ffffff;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background-color: var(--primary-color);
            color: var(--text-white);
            height: 100%;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
            flex-grow: 1;
        }

        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: 0.2s;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .menu-item:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .menu-item i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        /* Submenu Styling */
        .submenu {
            background-color: rgba(0,0,0,0.1);
            padding-left: 20px;
            display: block; /* Selalu tampil atau bisa diatur via JS */
        }

        .submenu-item {
            padding: 10px 25px;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
            cursor: pointer;
        }

        .submenu-item:hover {
            color: white;
        }

        /* Hide text when collapsed */
        .collapsed .menu-text, .collapsed .sidebar-header span {
            display: none;
        }

        .collapsed .submenu {
            display: none;
        }

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
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        #toggle-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-color);
        }

        .content-area {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-color);
        }

    </style>
</head>
<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-layer-group"></i> <span>DASHBOARD</span>
        </div>
        <div class="sidebar-menu">
            <a href="#" class="menu-item">
                <i class="fas fa-download"></i>
                <span class="menu-text">Incoming</span>
            </a>
            
            <div>
                <div class="menu-item">
                    <i class="fas fa-sync-alt"></i>
                    <span class="menu-text">Proses</span>
                </div>
                <div class="submenu">
                    <div class="submenu-item"><i class="fas fa-minus small"></i> <span class="menu-text"><a href="wiremesh/index.php">WM</a></span></div>
                    <div class="submenu-item"><i class="fas fa-minus small"></i> <span class="menu-text">Bronjong</span></div>
                </div>
            </div>

            <a href="#" class="menu-item">
                <i class="fas fa-upload"></i>
                <span class="menu-text">Outgoing</span>
            </a>
        </div>
    </nav>

    <div id="main-content">
        <header>
            <button id="toggle-btn"><i class="fas fa-bars"></i></button>
            <h2 style="margin-left: 20px; color: #333;">Sistem Inspeksi QC</h2>
        </header>

        <main class="content-area">
            <div class="card">
                <h3>Selamat Datang</h3>
                <p>Silahkan pilih menu di samping untuk melakukan inspeksi</p>
            </div>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>