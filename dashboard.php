<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
$nama_admin = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Sistem Manajemen Toko</title>
    <meta name="description" content="Dashboard admin untuk manajemen toko: produk, pelanggan, transaksi, stok.">
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Set theme immediately to prevent FOUC
        const savedTheme = localStorage.getItem('theme') || 'light';
        if (savedTheme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</head>
<body>
    <div class="dashboard-container">

        <!-- SIDEBAR OVERLAY (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i data-lucide="zap" class="icon" style="width:22px;height:22px;color:var(--accent-light);"></i> Admin Panel</h2>
                <p class="user-info">Halo, <span id="userName"><?= htmlspecialchars($nama_admin) ?></span></p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" data-section="dashboard" class="active"><span class="menu-icon"><i data-lucide="layout-dashboard" class="icon"></i></span> Dashboard</a></li>
                <li><a href="#" data-section="customers"><span class="menu-icon"><i data-lucide="users" class="icon"></i></span> Pelanggan</a></li>
                <li><a href="#" data-section="products"><span class="menu-icon"><i data-lucide="package" class="icon"></i></span> Produk</a></li>

                <li><a href="#" data-section="purchases"><span class="menu-icon"><i data-lucide="shopping-cart" class="icon"></i></span> Restock</a></li>
                <li><a href="#" data-section="transactions"><span class="menu-icon"><i data-lucide="receipt" class="icon"></i></span> Penjualan</a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="topbar">
                <div class="topbar-left">
                    <button class="hamburger" id="hamburgerBtn" aria-label="Toggle Menu">
                        <span></span><span></span><span></span>
                    </button>
                    <h1 id="pageTitle">Dashboard</h1>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button id="themeToggleBtn" class="btn" style="border-radius:50%; width:40px; height:40px; padding:0; display:flex; align-items:center; justify-content:center; background:var(--surface); color:var(--text); box-shadow:var(--shadow);" aria-label="Toggle Theme">
                        <i data-lucide="moon" id="themeIcon"></i>
                    </button>
                    <button class="logout-btn" id="logoutBtn">Logout</button>
                </div>
            </div>

            <div class="page-content">
                <?php include 'views/dashboard_home.php'; ?>
                <?php include 'views/pelanggan.php'; ?>
                <?php include 'views/produk.php'; ?>

                <?php include 'views/pembelian.php'; ?>
                <?php include 'views/transaksi.php'; ?>
            </div>
        </div>
    </div>

    <?php include 'views/modals.php'; ?>

    <script src="js/app.js?v=<?= time() ?>"></script>
</body>
</html>