<!-- ====== KATALOG (E-Commerce Style) ====== -->
<div id="catalogSection" class="content-section">
    <div class="section-header">
        <h2>Katalog Produk</h2>
    </div>
    <div class="catalog-container">
        <div class="catalog-sidebar">
            <div class="filter-panel">
                <h3><i data-lucide="sliders-horizontal" class="icon"></i> Filter</h3>

                <div class="filter-group">
                    <label>Cari Produk</label>
                    <input type="text" id="catalogSearch" placeholder="Ketik nama produk..." oninput="applyCatalogFilter()">
                </div>

                <div class="filter-group">
                    <label>Urutkan</label>
                    <select id="catalogSort" onchange="applyCatalogFilter()">
                        <option value="default">Default</option>
                        <option value="az">Nama: A → Z</option>
                        <option value="za">Nama: Z → A</option>
                        <option value="price_low">Harga: Terendah</option>
                        <option value="price_high">Harga: Tertinggi</option>
                        <option value="stock_low">Stok: Terendah</option>
                        <option value="stock_high">Stok: Tertinggi</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Rentang Harga</label>
                    <div class="price-range">
                        <input type="number" id="priceMin" placeholder="Min" min="0" oninput="applyCatalogFilter()">
                        <span>—</span>
                        <input type="number" id="priceMax" placeholder="Max" min="0" oninput="applyCatalogFilter()">
                    </div>
                </div>

                <div class="filter-group">
                    <label>Kategori / Jenis</label>
                    <div class="filter-tags" id="categoryTags">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <div class="filter-group">
                    <label>Stok</label>
                    <select id="catalogStockFilter" onchange="applyCatalogFilter()">
                        <option value="all">Semua</option>
                        <option value="in_stock">Tersedia (>10)</option>
                        <option value="low_stock">Stok Rendah (1-10)</option>
                        <option value="out_stock">Habis (0)</option>
                    </select>
                </div>

                <button class="btn-reset-filter" onclick="resetCatalogFilter()">↻ Reset Filter</button>
            </div>
        </div>
        <div class="catalog-main">
            <div class="catalog-info">
                <span id="catalogCount">Menampilkan 0 produk</span>
                <select class="filter-select" id="catalogSortTop" onchange="document.getElementById('catalogSort').value=this.value;applyCatalogFilter();" style="min-width:140px;">
                    <option value="default">Default</option>
                    <option value="az">A → Z</option>
                    <option value="za">Z → A</option>
                    <option value="price_low">Harga ↑</option>
                    <option value="price_high">Harga ↓</option>
                </select>
            </div>
            <div class="product-grid" id="productGrid">
                <!-- Filled by JS -->
            </div>
        </div>
    </div>
</div>
