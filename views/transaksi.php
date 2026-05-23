<!-- ====== TRANSAKSI ====== -->
<div id="transactionsSection" class="content-section">
    <div class="section-header">
        <h2>Transaksi Penjualan</h2>
        <button class="btn btn-primary" onclick="showTransactionModal()">+ Buat Transaksi</button>
    </div>
    <div class="filter-bar">
        <div class="search-box">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" id="searchTransaction" placeholder="Cari transaksi..." oninput="filterTransactions()">
        </div>
        <select class="filter-select" id="sortTransaction" onchange="filterTransactions()">
            <option value="default">Urutkan: Terbaru</option>
            <option value="oldest">Terlama</option>
            <option value="total_high">Total: Tertinggi</option>
            <option value="total_low">Total: Terendah</option>
        </select>
    </div>
    <div class="table-container">
        <table class="table" id="transactionsTable">
            <thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Admin</th><th>Total</th><th>Aksi</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>
