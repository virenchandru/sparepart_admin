// ===== HELPERS =====
const apiGet  = (action, extra='') => fetch(`api.php?action=${action}${extra}`).then(r=>r.json());
const apiPost = (action, body)     => fetch(`api.php?action=${action}`, {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)}).then(r=>r.json());

const closeModal = id => document.getElementById(id).classList.remove('show');
const openModal  = id => document.getElementById(id).classList.add('show');

function showAlert(msg, type='success') {
    const d = document.createElement('div');
    d.className = `alert alert-${type} show`;
    d.textContent = msg;
    d.style.cssText = 'position:fixed;top:20px;right:20px;z-index:10000;width:340px;max-width:90vw;';
    document.body.appendChild(d);
    setTimeout(()=> {
        d.style.opacity = '0';
        d.style.transform = 'translateX(100%)';
        d.style.transition = 'all 0.3s ease';
        setTimeout(()=>d.remove(), 300);
    }, 3000);
}

const rupiah = n => 'Rp ' + parseInt(n).toLocaleString('id-ID');

// Animated counter
function animateCount(el, target) {
    const dur = 600;
    const start = performance.now();
    const from = 0;
    function update(now) {
        const progress = Math.min((now - start) / dur, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.floor(from + (target - from) * eased);
        if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
}

// ===== HAMBURGER MENU =====
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const hamburger = document.getElementById('hamburgerBtn');

hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});

// ===== NAVIGASI =====
const titles = {
    dashboard:'Dashboard', customers:'Manajemen Pelanggan', products:'Manajemen Produk',
    catalog:'Katalog Produk', purchases:'Pembelian / Restock',
    transactions:'Transaksi Penjualan'
};

document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        const sec = link.dataset.section;
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
        document.getElementById(`${sec}Section`).classList.add('active');
        document.querySelectorAll('.sidebar-menu a').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        document.getElementById('pageTitle').textContent = titles[sec] || '';
        // Close sidebar on mobile
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        // Load data
        if (sec==='dashboard')    loadStats();
        if (sec==='customers')    loadCustomers();
        if (sec==='products')     loadProducts();
        if (sec==='catalog')      loadCatalog();
        if (sec==='purchases')    loadPurchases();
        if (sec==='transactions') loadTransactions();
    });
});

document.getElementById('logoutBtn').addEventListener('click', async () => {
    if (confirm('Yakin ingin logout?')) {
        await apiPost('logout', {});
        window.location.href = 'login.php';
    }
});

// ===== STATS =====
async function loadStats() {
    const d = await apiGet('get_stats');
    animateCount(document.getElementById('totalCustomers'), +d.total_pelanggan);
    animateCount(document.getElementById('totalProducts'), +d.total_produk);
    animateCount(document.getElementById('totalTransactions'), +d.total_transaksi);
    animateCount(document.getElementById('totalPurchases'), +d.total_pembelian);

    // Produk Laris
    document.getElementById('produkLaris').textContent = d.produk_laris || '-';
    document.getElementById('produkLarisQty').textContent = d.produk_laris_qty ? `Terjual ${d.produk_laris_qty} unit` : '';

    // Produk Tidak Laris
    document.getElementById('produkTidakLaris').textContent = d.produk_tidak_laris || '-';
    document.getElementById('produkTidakLarisQty').textContent = d.produk_tidak_laris_qty !== undefined ? `Terjual ${d.produk_tidak_laris_qty} unit` : '';

    // Top Pelanggan
    document.getElementById('topPelanggan').textContent = d.top_pelanggan || '-';
    document.getElementById('topPelangganQty').textContent = d.top_pelanggan_transaksi ? `${d.top_pelanggan_transaksi} transaksi` : '';

    lucide.createIcons();
}

// ===== DATA STORES =====
let allCustomers = [];
let allProducts = [];
let allPurchases = [];
let allTransactions = [];
let allStock = [];
let catalogProducts = [];
let pelangganList = [];
let produkList = [];

// ===== PELANGGAN =====
let alamatSuggestions = [];

async function loadCustomers() {
    allCustomers = await apiGet('get_pelanggan');
    buildAlamatSuggestions();
    filterCustomers();
}

function buildAlamatSuggestions() {
    // Extract unique address parts from all customers
    const alamatSet = new Set();
    allCustomers.forEach(c => {
        if (c.alamat) {
            // Split by common delimiters
            const parts = c.alamat.split(/[,;\-\/]+/).map(s => s.trim()).filter(s => s.length > 2);
            parts.forEach(p => alamatSet.add(p));
            // Also add the full alamat
            alamatSet.add(c.alamat.trim());
        }
    });
    alamatSuggestions = [...alamatSet].sort((a, b) => a.localeCompare(b));
}

function onAlamatInput() {
    const input = document.getElementById('filterCustomerAlamat');
    const q = input.value.toLowerCase();
    const dropdown = document.getElementById('alamatDropdown');
    if (!q) {
        dropdown.classList.remove('show');
        filterCustomers();
        return;
    }
    const matches = alamatSuggestions.filter(a => a.toLowerCase().includes(q));
    renderAlamatDropdown(matches);
    dropdown.classList.add('show');
    filterCustomers();
}

function onAlamatFocus() {
    const input = document.getElementById('filterCustomerAlamat');
    const q = input.value.toLowerCase();
    const dropdown = document.getElementById('alamatDropdown');
    const matches = q ? alamatSuggestions.filter(a => a.toLowerCase().includes(q)) : alamatSuggestions;
    renderAlamatDropdown(matches);
    dropdown.classList.add('show');
}

function renderAlamatDropdown(data) {
    const dropdown = document.getElementById('alamatDropdown');
    if (data.length === 0) {
        dropdown.innerHTML = '<div class="searchable-item empty">Tidak ditemukan</div>';
        return;
    }
    const q = document.getElementById('filterCustomerAlamat').value.toLowerCase();
    dropdown.innerHTML = data.map(a => {
        // Highlight matching text
        let display = a;
        if (q) {
            const idx = a.toLowerCase().indexOf(q);
            if (idx >= 0) {
                display = a.substring(0, idx) + '<strong style="color:var(--accent);">' + a.substring(idx, idx + q.length) + '</strong>' + a.substring(idx + q.length);
            }
        }
        // Count matching customers
        const count = allCustomers.filter(c => c.alamat && c.alamat.toLowerCase().includes(a.toLowerCase())).length;
        return `<div class="searchable-item" onclick="selectAlamat('${a.replace(/'/g, "\\'")}')">
            <span class="searchable-item-name">${display}</span>
            <span class="searchable-item-sub">${count} pelanggan</span>
        </div>`;
    }).join('');
}

function selectAlamat(alamat) {
    document.getElementById('filterCustomerAlamat').value = alamat;
    document.getElementById('alamatDropdown').classList.remove('show');
    filterCustomers();
}

function filterCustomers() {
    const q = document.getElementById('searchCustomer').value.toLowerCase();
    const alamatQ = document.getElementById('filterCustomerAlamat').value.toLowerCase();
    const sort = document.getElementById('sortCustomer').value;
    let data = [...allCustomers];
    if (q) data = data.filter(c => c.nama.toLowerCase().includes(q) || c.no_hp.includes(q));
    if (alamatQ) data = data.filter(c => c.alamat && c.alamat.toLowerCase().includes(alamatQ));
    if (sort === 'az') data.sort((a,b) => a.nama.localeCompare(b.nama));
    if (sort === 'za') data.sort((a,b) => b.nama.localeCompare(a.nama));
    renderCustomers(data);
}

function renderCustomers(data) {
    document.querySelector('#customersTable tbody').innerHTML = data.length === 0
        ? '<tr><td colspan="5" class="no-data">Tidak ada data pelanggan</td></tr>'
        : data.map(c=>`<tr>
            <td>${c.id_pelanggan}</td><td>${c.nama}</td><td>${c.no_hp}</td><td>${c.alamat}</td>
            <td><div class="action-buttons">
                <button class="btn btn-primary btn-small" onclick="editCustomer(${c.id_pelanggan},'${c.nama}','${c.no_hp}','${encodeURIComponent(c.alamat)}')">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteCustomer(${c.id_pelanggan})">Hapus</button>
            </div></td></tr>`).join('');
}

function showCustomerModal() {
    document.getElementById('customerModalTitle').textContent = 'Tambah Pelanggan';
    ['customerId','namaCustomer','noHpCustomer','alamatCustomer'].forEach(id=>document.getElementById(id).value='');
    openModal('customerModal');
}

function editCustomer(id, nama, no_hp, alamat) {
    document.getElementById('customerModalTitle').textContent = 'Edit Pelanggan';
    document.getElementById('customerId').value   = id;
    document.getElementById('namaCustomer').value = nama;
    document.getElementById('noHpCustomer').value = no_hp;
    document.getElementById('alamatCustomer').value = decodeURIComponent(alamat);
    openModal('customerModal');
}

async function saveCustomer() {
    const id    = document.getElementById('customerId').value;
    const nama  = document.getElementById('namaCustomer').value;
    const no_hp = document.getElementById('noHpCustomer').value;
    const alamat = document.getElementById('alamatCustomer').value;
    if (!nama||!no_hp||!alamat) { showAlert('Semua field wajib diisi','danger'); return; }
    const res = await apiPost(id?'update_pelanggan':'add_pelanggan', id?{id_pelanggan:+id,nama,no_hp,alamat}:{nama,no_hp,alamat});
    if (res.success) { showAlert(id?'Pelanggan diperbarui':'Pelanggan ditambahkan'); closeModal('customerModal'); loadCustomers(); }
    else showAlert(res.message||'Gagal','danger');
}

async function deleteCustomer(id) {
    if (!confirm('Hapus pelanggan ini?')) return;
    const res = await apiPost('delete_pelanggan',{id});
    if (res.success) { showAlert('Pelanggan dihapus'); loadCustomers(); }
    else showAlert('Gagal menghapus','danger');
}

// ===== PRODUK (Unified: Table + Grid) =====
let currentProductView = 'table';

async function loadProducts() {
    allProducts = await apiGet('get_produk');
    buildCategoryFilter();
    filterProducts();
    lucide.createIcons();
}

function buildCategoryFilter() {
    const select = document.getElementById('filterProductCategory');
    const currentVal = select.value;
    const categories = [...new Set(allProducts.map(p => p.jenis_produk).filter(Boolean))].sort();
    select.innerHTML = '<option value="all">Semua Kategori</option>' +
        categories.map(c => `<option value="${c}">${c}</option>`).join('');
    if ([...select.options].some(o => o.value === currentVal)) {
        select.value = currentVal;
    }
}

function setProductView(view) {
    currentProductView = view;
    document.querySelectorAll('.view-toggle-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.view-toggle-btn[data-view="${view}"]`).classList.add('active');
    document.getElementById('productTableView').style.display = view === 'table' ? 'block' : 'none';
    document.getElementById('productGridView').style.display = view === 'grid' ? 'block' : 'none';
    filterProducts();
}

function filterProducts() {
    const q = document.getElementById('searchProduct').value.toLowerCase();
    const sort = document.getElementById('sortProduct').value;
    const category = document.getElementById('filterProductCategory').value;
    const stockFilter = document.getElementById('filterProductStock').value;
    let data = [...allProducts];

    // Search
    if (q) data = data.filter(p => p.nama_produk.toLowerCase().includes(q) || p.jenis_produk.toLowerCase().includes(q));

    // Category
    if (category !== 'all') data = data.filter(p => p.jenis_produk === category);

    // Stock
    if (stockFilter === 'in_stock') data = data.filter(p => +p.stok > 10);
    if (stockFilter === 'low_stock') data = data.filter(p => +p.stok > 0 && +p.stok <= 10);
    if (stockFilter === 'out_stock') data = data.filter(p => +p.stok === 0);

    // Sort
    switch(sort) {
        case 'az': data.sort((a,b) => a.nama_produk.localeCompare(b.nama_produk)); break;
        case 'za': data.sort((a,b) => b.nama_produk.localeCompare(a.nama_produk)); break;
        case 'price_low': data.sort((a,b) => +a.harga - +b.harga); break;
        case 'price_high': data.sort((a,b) => +b.harga - +a.harga); break;
        case 'stock_low': data.sort((a,b) => +a.stok - +b.stok); break;
        case 'stock_high': data.sort((a,b) => +b.stok - +a.stok); break;
    }

    // Update count
    document.getElementById('productCount').textContent = `Menampilkan ${data.length} produk`;

    // Render based on current view
    renderProducts(data);
    renderProductGrid(data);
}

function renderProducts(data) {
    document.querySelector('#productsTable tbody').innerHTML = data.length === 0
        ? '<tr><td colspan="6" class="no-data">Tidak ada data produk</td></tr>'
        : data.map(p => {
            const badge = p.stok > 10 ? 'success' : (p.stok > 0 ? 'warning' : 'danger');
            return `<tr>
                <td>${p.id_produk}</td><td>${p.nama_produk}</td><td>${rupiah(p.harga)}</td>
                <td><span class="badge badge-${badge}">${p.stok} unit</span></td>
                <td>${p.jenis_produk}</td>
                <td><div class="action-buttons">
                    <button class="btn btn-primary btn-small" onclick="editProduct(${p.id_produk},'${encodeURIComponent(p.nama_produk)}',${p.harga},${p.stok},'${encodeURIComponent(p.jenis_produk)}')">Edit</button>
                    <button class="btn btn-danger btn-small" onclick="deleteProduct(${p.id_produk})">Hapus</button>
                </div></td></tr>`;
          }).join('');
}

function renderProductGrid(data) {
    const grid = document.getElementById('productGrid');
    if (data.length === 0) {
        grid.innerHTML = `<div class="catalog-empty" style="grid-column:1/-1;">
            <div class="empty-icon"><i data-lucide="package-open"></i></div>
            <p>Tidak ada produk yang cocok dengan filter.</p>
        </div>`;
        lucide.createIcons();
        return;
    }
    grid.innerHTML = data.map((p, i) => {
        const stok = +p.stok;
        let stockClass = 'in-stock', stockText = `${stok} tersedia`;
        if (stok === 0) { stockClass = 'out-stock'; stockText = 'Habis'; }
        else if (stok <= 10) { stockClass = 'low-stock'; stockText = `${stok} tersisa`; }
        return `<div class="product-card" style="animation-delay:${i*0.05}s">
            <div class="product-card-icon"><i data-lucide="box"></i></div>
            <div class="product-card-category">${p.jenis_produk}</div>
            <div class="product-card-name" title="${p.nama_produk}">${p.nama_produk}</div>
            <div class="product-card-price">${rupiah(p.harga)}</div>
            <span class="product-card-stock ${stockClass}">${stockText}</span>
            <div class="product-card-actions" style="margin-top:12px;display:flex;gap:6px;">
                <button class="btn btn-primary btn-small" onclick="editProduct(${p.id_produk},'${encodeURIComponent(p.nama_produk)}',${p.harga},${p.stok},'${encodeURIComponent(p.jenis_produk)}')">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteProduct(${p.id_produk})">Hapus</button>
            </div>
        </div>`;
    }).join('');
    lucide.createIcons();
}

function showProductModal() {
    document.getElementById('productModalTitle').textContent = 'Tambah Produk';
    ['productId','namaProduk','hargaProduk','stokProduk','jenisProduk'].forEach(id=>document.getElementById(id).value='');
    openModal('productModal');
}

function editProduct(id, nama, harga, stok, jenis) {
    document.getElementById('productModalTitle').textContent = 'Edit Produk';
    document.getElementById('productId').value = id;
    document.getElementById('namaProduk').value = decodeURIComponent(nama);
    document.getElementById('hargaProduk').value = harga;
    document.getElementById('stokProduk').value = stok;
    document.getElementById('jenisProduk').value = decodeURIComponent(jenis);
    openModal('productModal');
}

async function saveProduct() {
    const id = document.getElementById('productId').value;
    const nama_produk = document.getElementById('namaProduk').value;
    const harga = document.getElementById('hargaProduk').value;
    const stok = document.getElementById('stokProduk').value;
    const jenis_produk = document.getElementById('jenisProduk').value;

    if (!nama_produk || !harga || !stok || !jenis_produk) {
        showAlert('Semua field wajib diisi', 'danger');
        return;
    }

    const body = id ? { id_produk: +id, nama_produk, harga, stok, jenis_produk } : { nama_produk, harga, stok, jenis_produk };
    const res = await apiPost(id ? 'update_produk' : 'add_produk', body);
    
    if (res.success) {
        showAlert(id ? 'Produk diperbarui' : 'Produk ditambahkan');
        closeModal('productModal');
        loadProducts();
    } else {
        showAlert(res.message || 'Gagal', 'danger');
    }
}

async function deleteProduct(id) {
    if (!confirm('Hapus produk ini?')) return;
    const res = await apiPost('delete_produk', { id });
    if (res.success) {
        showAlert('Produk dihapus');
        loadProducts();
    } else {
        showAlert('Gagal menghapus', 'danger');
    }
}

// ===== PEMBELIAN =====
let pembelianProdukList = [];

async function loadPurchases() {
    allPurchases = await apiGet('get_pembelian');
    filterPurchases();
}

function filterPurchases() {
    const q = document.getElementById('searchPurchase').value.toLowerCase();
    const status = document.getElementById('filterPurchaseStatus').value;
    let data = [...allPurchases];
    if (q) data = data.filter(p => p.nama_produk.toLowerCase().includes(q) || p.tanggal.includes(q));
    if (status !== 'all') {
        if (status === 'pending') {
            data = data.filter(p => ['pending', 'diproses', 'dikirim'].includes(p.status));
        } else {
            data = data.filter(p => p.status === status);
        }
    }
    renderPurchases(data);
}

function renderPurchases(data) {
    document.querySelector('#purchasesTable tbody').innerHTML = data.length === 0
        ? '<tr><td colspan="7" class="no-data">Tidak ada data pembelian</td></tr>'
        : data.map(p=>{
            const badge = p.status==='diterima'?'success':(p.status==='pending'?'warning':'danger');
            return `<tr>
                <td>${p.id_pembelian}</td><td>${p.tanggal}</td><td>${p.nama_produk}</td>
                <td>${p.jumlah}</td><td>${rupiah(p.harga_beli)}</td>
                <td><span class="badge badge-${badge}">${p.status}</span></td>
                <td><div class="action-buttons">
                    <button class="btn btn-warning btn-small" onclick="openUpdateStatus(${p.id_pembelian},'${p.status}')">Update</button>
                    <button class="btn btn-danger btn-small" onclick="deletePurchase(${p.id_pembelian})">Hapus</button>
                </div></td></tr>`;
          }).join('');
}

async function showPurchaseModal() {
    const produk = await apiGet('get_produk');
    pembelianProdukList = produk;

    const searchInput = document.getElementById('produkPembelianSearchInput');
    const hiddenInput = document.getElementById('produkPembelianValue');
    const dropdown = document.getElementById('produkPembelianDropdown');

    searchInput.value = '';
    hiddenInput.value = '';
    renderProdukPembelianDropdown(produk);

    searchInput.oninput = () => {
        hiddenInput.value = '';
        const q = searchInput.value.toLowerCase();
        const filtered = pembelianProdukList.filter(p =>
            (p.nama_produk || '').toLowerCase().includes(q) ||
            (p.jenis_produk || '').toLowerCase().includes(q)
        );
        renderProdukPembelianDropdown(filtered);
        dropdown.classList.add('show');
    };

    searchInput.onfocus = () => {
        const q = searchInput.value.toLowerCase();
        const filtered = q ? pembelianProdukList.filter(p => p.nama_produk.toLowerCase().includes(q)) : pembelianProdukList;
        renderProdukPembelianDropdown(filtered);
        dropdown.classList.add('show');
    };

    document.getElementById('tanggalPembelian').valueAsDate = new Date();
    ['jumlahPembelian','hargaBeli'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('statusPembelian').value='';
    openModal('purchaseModal');
}

function renderProdukPembelianDropdown(data) {
    const dropdown = document.getElementById('produkPembelianDropdown');
    if (data.length === 0) {
        dropdown.innerHTML = '<div class="searchable-item empty">Tidak ditemukan</div>';
        return;
    }
    dropdown.innerHTML = data.map(p =>
        `<div class="searchable-item" onclick="selectProdukPembelian(${p.id_produk}, '${(p.nama_produk || '').replace(/'/g, "\\'")}')">
            <span class="searchable-item-name">${p.nama_produk}</span>
            <span class="searchable-item-sub">Jenis: ${p.jenis_produk || '-'} &middot; Stok: ${p.stok} &middot; Rp ${parseInt(p.harga).toLocaleString('id-ID')}</span>
        </div>`
    ).join('');
}

function selectProdukPembelian(id, nama) {
    document.getElementById('produkPembelianSearchInput').value = nama;
    document.getElementById('produkPembelianValue').value = id;
    document.getElementById('produkPembelianDropdown').classList.remove('show');
}

async function savePurchase() {
    const id_produk  = +document.getElementById('produkPembelianValue').value;
    const tanggal    = document.getElementById('tanggalPembelian').value;
    const jumlah     = +document.getElementById('jumlahPembelian').value;
    const harga_beli = +document.getElementById('hargaBeli').value;
    const status     = document.getElementById('statusPembelian').value;
    if (!id_produk||!tanggal||!jumlah||!status) { showAlert('Semua field wajib diisi','danger'); return; }
    const res = await apiPost('add_pembelian',{id_produk,tanggal,jumlah,harga_beli,status});
    if (res.success) { showAlert('Pembelian disimpan'); closeModal('purchaseModal'); loadPurchases(); }
    else showAlert(res.message||'Gagal','danger');
}

function openUpdateStatus(id, status) {
    document.getElementById('updatePembelianId').value     = id;
    document.getElementById('statusPembelianBaru').value   = status;
    openModal('statusPembelianModal');
}

async function updateStatusPembelian() {
    const id     = +document.getElementById('updatePembelianId').value;
    const status = document.getElementById('statusPembelianBaru').value;
    const res    = await apiPost('update_status_pembelian',{id,status});
    if (res.success) { showAlert('Status diperbarui'); closeModal('statusPembelianModal'); loadPurchases(); }
    else showAlert('Gagal','danger');
}

async function deletePurchase(id) {
    if (!confirm('Hapus pembelian ini?')) return;
    const res = await apiPost('delete_pembelian',{id});
    if (res.success) { showAlert('Pembelian dihapus'); loadPurchases(); }
    else showAlert('Gagal','danger');
}

// ===== TRANSAKSI =====
let transactionItems = [];

async function loadTransactions() {
    allTransactions = await apiGet('get_transaksi');
    filterTransactions();
}

function filterTransactions() {
    const q = document.getElementById('searchTransaction').value.toLowerCase();
    const sort = document.getElementById('sortTransaction').value;
    let data = [...allTransactions];
    if (q) data = data.filter(t => t.nama_pelanggan.toLowerCase().includes(q) || t.nama_admin.toLowerCase().includes(q) || t.tanggal.includes(q));
    switch(sort) {
        case 'oldest': data.sort((a,b)=>a.id_transaksi-b.id_transaksi); break;
        case 'total_high': data.sort((a,b)=>(+b.total)-(+a.total)); break;
        case 'total_low': data.sort((a,b)=>(+a.total)-(+b.total)); break;
    }
    renderTransactions(data);
}

function renderTransactions(data) {
    document.querySelector('#transactionsTable tbody').innerHTML = data.length === 0
        ? '<tr><td colspan="6" class="no-data">Tidak ada data transaksi</td></tr>'
        : data.map(t=>`<tr>
            <td>${t.id_transaksi}</td><td>${t.tanggal}</td>
            <td>${t.nama_pelanggan}</td><td>${t.nama_admin}</td><td>${rupiah(t.total)}</td>
            <td><div class="action-buttons">
                <button class="btn btn-primary btn-small" onclick="viewDetail(${t.id_transaksi})">Detail</button>
                <button class="btn btn-danger btn-small" onclick="deleteTransaction(${t.id_transaksi})">Hapus</button>
            </div></td></tr>`).join('');
}

async function showTransactionModal() {
    transactionItems = [];
    document.getElementById('transactionItems').innerHTML = '';
    document.getElementById('transactionGrandTotal').textContent = 'Rp 0';
    const [pelanggan, produk] = await Promise.all([apiGet('get_pelanggan'), apiGet('get_produk')]);

    // Searchable customer dropdown
    pelangganList = pelanggan;
    const searchInput = document.getElementById('pelangganSearchInput');
    const hiddenInput = document.getElementById('pelangganTransaksiValue');
    const dropdown = document.getElementById('pelangganDropdown');

    searchInput.value = '';
    hiddenInput.value = '';
    renderPelangganDropdown(pelanggan);

    searchInput.oninput = () => {
        hiddenInput.value = '';
        const q = searchInput.value.toLowerCase();
        const filtered = pelangganList.filter(p => p.nama.toLowerCase().includes(q) || p.no_hp.includes(q));
        renderPelangganDropdown(filtered);
        dropdown.classList.add('show');
    };

    searchInput.onfocus = () => {
        const q = searchInput.value.toLowerCase();
        const filtered = q ? pelangganList.filter(p => p.nama.toLowerCase().includes(q)) : pelangganList;
        renderPelangganDropdown(filtered);
        dropdown.classList.add('show');
    };

    // Searchable product dropdown
    produkList = produk;
    const prodSearchInput = document.getElementById('produkSearchInput');
    const prodHiddenInput = document.getElementById('produkTransaksiValue');
    const prodDropdown = document.getElementById('produkDropdown');

    prodSearchInput.value = '';
    prodHiddenInput.value = '';
    prodSearchInput.dataset.nama = '';
    prodSearchInput.dataset.stok = '';
    renderProdukDropdown(produk);

    prodSearchInput.oninput = () => {
        prodHiddenInput.value = '';
        const q = prodSearchInput.value.toLowerCase();
        const filtered = produkList.filter(p => 
            (p.nama_produk || '').toLowerCase().includes(q) || 
            (p.jenis_produk || '').toLowerCase().includes(q)
        );
        renderProdukDropdown(filtered);
        prodDropdown.classList.add('show');
    };

    prodSearchInput.onfocus = () => {
        const q = prodSearchInput.value.toLowerCase();
        const filtered = q ? produkList.filter(p => p.nama_produk.toLowerCase().includes(q)) : produkList;
        renderProdukDropdown(filtered);
        prodDropdown.classList.add('show');
    };

    ['jumlahTransaksi','hargaTransaksi'].forEach(id=>document.getElementById(id).value='');
    openModal('transactionModal');
}

function renderPelangganDropdown(data) {
    const dropdown = document.getElementById('pelangganDropdown');
    if (data.length === 0) {
        dropdown.innerHTML = '<div class="searchable-item empty">Tidak ditemukan</div>';
        return;
    }
    dropdown.innerHTML = data.map(p =>
        `<div class="searchable-item" data-id="${p.id_pelanggan}" data-nama="${p.nama}" onclick="selectPelanggan(${p.id_pelanggan}, '${p.nama.replace(/'/g, "\\'")}')">
            <span class="searchable-item-name">${p.nama}</span>
            <span class="searchable-item-sub">${p.no_hp}</span>
        </div>`
    ).join('');
}

function selectPelanggan(id, nama) {
    document.getElementById('pelangganSearchInput').value = nama;
    document.getElementById('pelangganTransaksiValue').value = id;
    document.getElementById('pelangganDropdown').classList.remove('show');
}

function renderProdukDropdown(data) {
    const dropdown = document.getElementById('produkDropdown');
    if (data.length === 0) {
        dropdown.innerHTML = '<div class="searchable-item empty">Tidak ditemukan</div>';
        return;
    }
    dropdown.innerHTML = data.map(p =>
        `<div class="searchable-item" data-id="${p.id_produk}" data-nama="${p.nama_produk.replace(/'/g, "\\'")}" data-harga="${p.harga}" data-stok="${p.stok}" onclick="selectProduk(${p.id_produk}, '${p.nama_produk.replace(/'/g, "\\'")}', ${p.harga}, ${p.stok})">
            <span class="searchable-item-name">${p.nama_produk}</span>
            <span class="searchable-item-sub">Jenis: ${p.jenis_produk || '-'} &middot; Stok: ${p.stok} &middot; Rp ${parseInt(p.harga).toLocaleString('id-ID')}</span>
        </div>`
    ).join('');
}

function selectProduk(id, nama, harga, stok) {
    const prodInput = document.getElementById('produkSearchInput');
    prodInput.value = nama;
    prodInput.dataset.nama = nama;
    prodInput.dataset.stok = stok;
    document.getElementById('produkTransaksiValue').value = id;
    document.getElementById('hargaTransaksi').value = harga;
    document.getElementById('produkDropdown').classList.remove('show');
}

function addTransactionItem() {
    const id_produk = +document.getElementById('produkTransaksiValue').value;
    const prodInput = document.getElementById('produkSearchInput');
    const nama = prodInput.dataset.nama || '';
    const jumlah = +document.getElementById('jumlahTransaksi').value;
    const harga = +document.getElementById('hargaTransaksi').value;
    const stok = +prodInput.dataset.stok || 0;

    if (!id_produk || !jumlah || !harga) { showAlert('Pilih produk dan isi jumlah & harga', 'warning'); return; }
    if (jumlah > stok) { showAlert(`Stok tidak cukup (Tersedia: ${stok})`, 'danger'); return; }

    transactionItems.push({ id_produk, nama, jumlah, harga });
    renderTransactionItems();

    // Reset product selection inputs
    prodInput.value = '';
    document.getElementById('produkTransaksiValue').value = '';
    prodInput.dataset.nama = '';
    prodInput.dataset.stok = '';
    document.getElementById('jumlahTransaksi').value = '';
    document.getElementById('hargaTransaksi').value = '';
}

function renderTransactionItems() {
    let total = 0;
    document.getElementById('transactionItems').innerHTML = transactionItems.map((item,i)=>{
        const sub = item.jumlah * item.harga; total += sub;
        return `<div class="card" style="padding:14px;margin-bottom:8px;">
            <div class="flex" style="justify-content:space-between;align-items:center;">
                <div><strong>${item.nama}</strong><br><span style="color:var(--text-secondary);font-size:13px;">${item.jumlah} x ${rupiah(item.harga)} = ${rupiah(sub)}</span></div>
                <button type="button" class="btn btn-danger btn-small" onclick="removeItem(${i})">Hapus</button>
            </div></div>`;
    }).join('');
    document.getElementById('transactionGrandTotal').textContent = rupiah(total);
}

function removeItem(i) { transactionItems.splice(i,1); renderTransactionItems(); }

async function saveTransaction() {
    const id_pelanggan = +document.getElementById('pelangganTransaksiValue').value;
    if (!id_pelanggan) { showAlert('Pilih pelanggan','warning'); return; }
    if (!transactionItems.length) { showAlert('Tambahkan minimal satu item','warning'); return; }
    const res = await apiPost('add_transaksi',{id_pelanggan, items:transactionItems});
    if (res.success) { showAlert('Transaksi berhasil dibuat'); closeModal('transactionModal'); loadTransactions(); }
    else showAlert(res.message||'Gagal','danger');
}

async function viewDetail(id) {
    const data = await apiGet('get_detail_transaksi', `&id=${id}`);
    let grand = 0;
    const rows = data.map(d=>{ const sub=d.jumlah*d.harga; grand+=sub;
        return `<tr><td>${d.nama_produk}</td><td>${d.jumlah}</td><td>${rupiah(d.harga)}</td><td>${rupiah(sub)}</td></tr>`; }).join('');
    document.getElementById('detailsContent').innerHTML =
        `<p style="margin-bottom:12px;"><strong>ID Transaksi:</strong> ${id}</p><hr>
        <table class="table" style="width:100%"><thead><tr><th>Produk</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr></thead>
        <tbody>${rows}</tbody></table><hr>
        <h3 style="text-align:right">Grand Total: <span style="color:var(--accent);">${rupiah(grand)}</span></h3>`;
    openModal('detailsModal');
}

async function deleteTransaction(id) {
    if (!confirm('Hapus transaksi ini?')) return;
    const res = await apiPost('delete_transaksi',{id});
    if (res.success) { showAlert('Transaksi dihapus'); loadTransactions(); }
    else showAlert('Gagal','danger');
}


// ===== INIT =====
// Theme toggle logic
const toggleBtn = document.getElementById('themeToggleBtn');
const themeIcon = document.getElementById('themeIcon');

function updateThemeIcon() {
    if (!themeIcon) return;
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        themeIcon.setAttribute('data-lucide', 'sun');
    } else {
        themeIcon.setAttribute('data-lucide', 'moon');
    }
    lucide.createIcons();
}

if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme');
        const target = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', target);
        localStorage.setItem('theme', target);
        updateThemeIcon();
    });
}

lucide.createIcons();
updateThemeIcon();
loadStats();

// Close modal on overlay click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', e => {
        if (e.target === modal) modal.classList.remove('show');
    });
});

// ESC key to close modals
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.show').forEach(m => m.classList.remove('show'));
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }
});

// Close searchable dropdown on outside click
document.addEventListener('click', e => {
    const wrapper = document.getElementById('pelangganSearchWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        const dropdown = document.getElementById('pelangganDropdown');
        if (dropdown) dropdown.classList.remove('show');
    }
    const prodWrapper = document.getElementById('produkSearchWrapper');
    if (prodWrapper && !prodWrapper.contains(e.target)) {
        const dropdown = document.getElementById('produkDropdown');
        if (dropdown) dropdown.classList.remove('show');
    }
    const alamatWrapper = document.getElementById('alamatSearchWrapper');
    if (alamatWrapper && !alamatWrapper.contains(e.target)) {
        const dropdown = document.getElementById('alamatDropdown');
        if (dropdown) dropdown.classList.remove('show');
    }
    const pembelianProdWrapper = document.getElementById('produkPembelianSearchWrapper');
    if (pembelianProdWrapper && !pembelianProdWrapper.contains(e.target)) {
        const dropdown = document.getElementById('produkPembelianDropdown');
        if (dropdown) dropdown.classList.remove('show');
    }
});
