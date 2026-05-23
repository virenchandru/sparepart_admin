<!-- ====== DASHBOARD ====== -->
<div id="dashboardSection" class="content-section active">
    <div class="welcome-banner">
        <h2>Selamat Datang, <?= htmlspecialchars($nama_admin) ?></h2>
        <p>Kelola toko Anda dengan mudah melalui dashboard ini.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i data-lucide="users" class="icon"></i></div>
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value blue" id="totalCustomers">
                <span class="skeleton">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i data-lucide="package" class="icon"></i></div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value green" id="totalProducts">
                <span class="skeleton">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i data-lucide="receipt" class="icon"></i></div>
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value yellow" id="totalTransactions">
                <span class="skeleton">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i data-lucide="shopping-cart" class="icon"></i></div>
            <div class="stat-label">Pembelian / Restock</div>
            <div class="stat-value red" id="totalPurchases">
                <span class="skeleton">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
    </div>

    <!-- Insight Widgets -->
    <div class="insight-grid">
        <div class="insight-card" id="widgetLaris">
            <div class="insight-header">
                <div class="insight-icon green"><i data-lucide="trending-up" class="icon"></i></div>
                <span class="insight-title">Produk Paling Laris</span>
            </div>
            <div class="insight-body">
                <div class="insight-value" id="produkLaris">-</div>
                <div class="insight-sub" id="produkLarisQty"></div>
            </div>
        </div>
        <div class="insight-card" id="widgetTidakLaris">
            <div class="insight-header">
                <div class="insight-icon orange"><i data-lucide="trending-down" class="icon"></i></div>
                <span class="insight-title">Produk Kurang Laris</span>
            </div>
            <div class="insight-body">
                <div class="insight-value" id="produkTidakLaris">-</div>
                <div class="insight-sub" id="produkTidakLarisQty"></div>
            </div>
        </div>
        <div class="insight-card" id="widgetTopPelanggan">
            <div class="insight-header">
                <div class="insight-icon blue"><i data-lucide="crown" class="icon"></i></div>
                <span class="insight-title">Pelanggan Terbanyak Beli</span>
            </div>
            <div class="insight-body">
                <div class="insight-value" id="topPelanggan">-</div>
                <div class="insight-sub" id="topPelangganQty"></div>
            </div>
        </div>
    </div>
</div>
