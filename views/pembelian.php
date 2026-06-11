<!-- ====== PEMBELIAN ====== -->
<div id="purchasesSection" class="content-section">
    <div class="section-header">
        <h2>Pembelian / Restock</h2>
        <button class="btn btn-primary" onclick="showPurchaseModal()">+ Input Pembelian</button>
    </div>
    <div class="filter-bar">
        <div class="search-box">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" id="searchPurchase" placeholder="Cari pembelian..." oninput="filterPurchases()">
        </div>
        <select class="filter-select" id="filterPurchaseStatus" onchange="filterPurchases()">
            <option value="all">Semua</option>
            <option value="diproses" selected>Diproses</option>
            <option value="diterima">Diterima</option>
            <option value="dibatalkan">Dibatalkan</option>
        </select>
    </div>
    <div class="table-container">
        <table class="table" id="purchasesTable">
            <thead><tr><th>ID</th><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Harga Beli</th><th>Status</th><th>Penerima</th><th>Tgl Diterima</th><th>Aksi</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>
