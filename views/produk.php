<!-- ====== PRODUK (Unified: Table + Katalog) ====== -->
<div id="productsSection" class="content-section">
    <div class="section-header">
        <h2>Manajemen Produk</h2>
        <div style="display:flex;gap:10px;align-items:center;">
            <!-- View Toggle -->
            <div class="view-toggle" id="viewToggle">
                <button class="view-toggle-btn active" data-view="table" onclick="setProductView('table')" title="Tampilan Tabel">
                    <i data-lucide="list" class="icon"></i>
                </button>
                <button class="view-toggle-btn" data-view="grid" onclick="setProductView('grid')" title="Tampilan Katalog">
                    <i data-lucide="layout-grid" class="icon"></i>
                </button>
            </div>
            <button class="btn btn-primary" onclick="showProductModal()">+ Tambah Produk</button>
        </div>
    </div>

    <!-- Filter Bar (shared for both views) -->
    <div class="filter-bar">
        <div class="search-box">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" id="searchProduct" placeholder="Cari produk..." oninput="filterProducts()">
        </div>
        <select class="filter-select" id="filterProductCategory" onchange="filterProducts()">
            <option value="all">Semua Kategori</option>
        </select>
        <select class="filter-select" id="filterProductStock" onchange="filterProducts()">
            <option value="all">Semua Stok</option>
            <option value="in_stock">Tersedia (>10)</option>
            <option value="low_stock">Stok Rendah (1-10)</option>
            <option value="out_stock">Habis (0)</option>
        </select>
        <select class="filter-select" id="sortProduct" onchange="filterProducts()">
            <option value="default">Urutkan: Default</option>
            <option value="az">Nama A - Z</option>
            <option value="za">Nama Z - A</option>
            <option value="price_low">Harga: Terendah</option>
            <option value="price_high">Harga: Tertinggi</option>
            <option value="stock_low">Stok: Terendah</option>
            <option value="stock_high">Stok: Tertinggi</option>
        </select>
    </div>

    <!-- Product count -->
    <div class="catalog-info" id="productInfo" style="margin-bottom:16px;">
        <span id="productCount">Menampilkan 0 produk</span>
    </div>

    <!-- TABLE VIEW -->
    <div id="productTableView">
        <div class="table-container">
            <table class="table" id="productsTable">
                <thead><tr><th>ID</th><th>Nama Produk</th><th>Harga</th><th>Stok</th><th>Jenis</th><th>Aksi</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- GRID/CATALOG VIEW -->
    <div id="productGridView" style="display:none;">
        <div class="product-grid" id="productGrid">
            <!-- Filled by JS -->
        </div>
    </div>
</div>
