<?php
include 'session.php';
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <p class="user-info">Halo, <span id="userName"><?= htmlspecialchars($nama_admin) ?></span></p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" data-section="dashboard" class="active">Dashboard</a></li>
                <li><a href="#" data-section="customers">Pelanggan</a></li>
                <li><a href="#" data-section="products">Produk</a></li>
                <li><a href="#" data-section="purchases">Restock</a></li>
                <li><a href="#" data-section="transactions">Penjualan</a></li>
                <li><a href="#" data-section="stock">Stok</a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="topbar">
                <h1 id="pageTitle">Dashboard</h1>
                <button class="logout-btn" id="logoutBtn">Logout</button>
            </div>

            <div class="page-content">

                <!-- DASHBOARD -->
                <div id="dashboardSection" class="content-section active">
                    <h2>Selamat Datang, <?= htmlspecialchars($nama_admin) ?></h2>
                    <p>Kelola toko Anda dengan mudah melalui dashboard ini.</p>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 20px;">
                        <div class="card">
                            <div class="card-icon">👥</div>
                            <div class="card-header">Total Pelanggan</div>
                            <div style="font-size:24px;font-weight:bold;color:var(--accent-color);" id="totalCustomers">-</div>
                        </div>
                        <div class="card">
                            <div class="card-icon">📦</div>
                            <div class="card-header">Total Produk</div>
                            <div style="font-size:24px;font-weight:bold;color:var(--success-color);" id="totalProducts">-</div>
                        </div>
                        <div class="card">
                            <div class="card-icon">🧾</div>
                            <div class="card-header">Total Transaksi</div>
                            <div style="font-size:24px;font-weight:bold;color:var(--warning-color);" id="totalTransactions">-</div>
                        </div>
                        <div class="card">
                            <div class="card-icon">🛒</div>
                            <div class="card-header">Pembelian/Restock</div>
                            <div style="font-size:24px;font-weight:bold;color:var(--danger-color);" id="totalPurchases">-</div>
                        </div>
                    </div>
                    <div class="card mt-20" id="produkLarisCard" style="display:none;">
                        <div class="card-header">🏆 Produk Paling Laris</div>
                        <div style="font-size:18px;font-weight:bold;color:var(--accent-color);margin-top:8px;" id="produkLaris">-</div>
                    </div>
                </div>

                <!-- PELANGGAN -->
                <div id="customersSection" class="content-section">
                    <div class="section-header">
                        <h2>Manajemen Pelanggan</h2>
                        <button class="btn btn-primary" onclick="showCustomerModal()">+ Tambah Pelanggan</button>
                    </div>
                    <div class="table-container">
                        <table class="table" id="customersTable">
                            <thead><tr><th>ID</th><th>Nama</th><th>No HP</th><th>Alamat</th><th>Aksi</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- PRODUK -->
                <div id="productsSection" class="content-section">
                    <div class="section-header">
                        <h2>Manajemen Produk</h2>
                        <button class="btn btn-primary" onclick="showProductModal()">+ Tambah Produk</button>
                    </div>
                    <div class="table-container">
                        <table class="table" id="productsTable">
                            <thead><tr><th>ID</th><th>Nama Produk</th><th>Harga</th><th>Stok</th><th>Jenis</th><th>Aksi</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- PEMBELIAN -->
                <div id="purchasesSection" class="content-section">
                    <div class="section-header">
                        <h2>Pembelian / Restock Barang</h2>
                        <button class="btn btn-primary" onclick="showPurchaseModal()">+ Input Pembelian</button>
                    </div>
                    <div class="table-container">
                        <table class="table" id="purchasesTable">
                            <thead><tr><th>ID</th><th>Tanggal</th><th>Produk</th><th>Jumlah</th><th>Harga Beli</th><th>Status</th><th>Aksi</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- TRANSAKSI -->
                <div id="transactionsSection" class="content-section">
                    <div class="section-header">
                        <h2>Transaksi Penjualan</h2>
                        <button class="btn btn-primary" onclick="showTransactionModal()">+ Buat Transaksi</button>
                    </div>
                    <div class="table-container">
                        <table class="table" id="transactionsTable">
                            <thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Admin</th><th>Total</th><th>Aksi</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- STOK -->
                <div id="stockSection" class="content-section">
                    <div class="section-header"><h2>Manajemen Stok</h2></div>
                    <div class="table-container">
                        <table class="table" id="stockTable">
                            <thead><tr><th>ID</th><th>Nama Produk</th><th>Stok</th><th>Status</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL PELANGGAN -->
    <div id="customerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span id="customerModalTitle">Tambah Pelanggan</span>
                <button class="close-btn" onclick="closeModal('customerModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="customerId">
                <div class="form-group"><label>Nama</label><input type="text" id="namaCustomer"></div>
                <div class="form-group"><label>No HP</label><input type="text" id="noHpCustomer"></div>
                <div class="form-group"><label>Alamat</label><textarea id="alamatCustomer" rows="3"></textarea></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('customerModal')">Batal</button>
                <button class="btn btn-primary" onclick="saveCustomer()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL PRODUK -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span id="productModalTitle">Tambah Produk</span>
                <button class="close-btn" onclick="closeModal('productModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="productId">
                <div class="form-group"><label>Nama Produk</label><input type="text" id="namaProduk"></div>
                <div class="form-group"><label>Harga</label><input type="number" id="hargaProduk" min="0"></div>
                <div class="form-group"><label>Stok</label><input type="number" id="stokProduk" min="0"></div>
                <div class="form-group"><label>Jenis Produk</label><input type="text" id="jenisProduk"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('productModal')">Batal</button>
                <button class="btn btn-primary" onclick="saveProduct()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL PEMBELIAN -->
    <div id="purchaseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Input Pembelian Barang</span>
                <button class="close-btn" onclick="closeModal('purchaseModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Tanggal</label><input type="date" id="tanggalPembelian"></div>
                <div class="form-group"><label>Produk</label>
                    <select id="produkPembelian"><option value="">-- Pilih Produk --</option></select>
                </div>
                <div class="form-group"><label>Jumlah</label><input type="number" id="jumlahPembelian" min="1"></div>
                <div class="form-group"><label>Harga Beli (per unit)</label><input type="number" id="hargaBeli" min="0"></div>
                <div class="form-group"><label>Status</label>
                    <select id="statusPembelian">
                        <option value="">-- Pilih Status --</option>
                        <option value="diterima">Diterima</option>
                        <option value="pending">Pending</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('purchaseModal')">Batal</button>
                <button class="btn btn-primary" onclick="savePurchase()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL UPDATE STATUS PEMBELIAN -->
    <div id="statusPembelianModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Update Status Pembelian</span>
                <button class="close-btn" onclick="closeModal('statusPembelianModal')">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="updatePembelianId">
                <div class="form-group"><label>Status</label>
                    <select id="statusPembelianBaru">
                        <option value="pending">Pending</option>
                        <option value="diterima">Diterima</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('statusPembelianModal')">Batal</button>
                <button class="btn btn-primary" onclick="updateStatusPembelian()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- MODAL TRANSAKSI -->
    <div id="transactionModal" class="modal">
        <div class="modal-content" style="max-width:600px;">
            <div class="modal-header">
                <span>Buat Transaksi Penjualan</span>
                <button class="close-btn" onclick="closeModal('transactionModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Pelanggan</label>
                    <select id="pelangganTransaksi"><option value="">-- Pilih Pelanggan --</option></select>
                </div>
                <hr>
                <h4>Tambah Item</h4>
                <div class="form-group"><label>Produk</label>
                    <select id="produkTransaksi"><option value="">-- Pilih Produk --</option></select>
                </div>
                <div class="form-group"><label>Jumlah</label><input type="number" id="jumlahTransaksi" min="1"></div>
                <div class="form-group"><label>Harga (per unit)</label><input type="number" id="hargaTransaksi" min="0"></div>
                <button type="button" class="btn btn-secondary" onclick="addTransactionItem()">Tambah Item</button>
                <hr>
                <h4>Item Transaksi</h4>
                <div id="transactionItems"></div>
                <h3 style="text-align:right;margin-top:15px;">Grand Total: <span id="transactionGrandTotal">Rp 0</span></h3>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('transactionModal')">Batal</button>
                <button class="btn btn-primary" onclick="saveTransaction()">Buat Transaksi</button>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL TRANSAKSI -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Detail Transaksi</span>
                <button class="close-btn" onclick="closeModal('detailsModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailsContent"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('detailsModal')">Tutup</button>
            </div>
        </div>
    </div>

    <script>
    // ===== HELPERS =====
    const apiGet  = (action, extra='') => fetch(`api.php?action=${action}${extra}`).then(r=>r.json());
    const apiPost = (action, body)     => fetch(`api.php?action=${action}`, {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)}).then(r=>r.json());

    const closeModal = id => document.getElementById(id).classList.remove('show');
    const openModal  = id => document.getElementById(id).classList.add('show');

    function showAlert(msg, type='success') {
        const d = document.createElement('div');
        d.className = `alert alert-${type} show`;
        d.textContent = msg;
        d.style.cssText = 'position:fixed;top:20px;right:20px;z-index:10000;width:300px;';
        document.body.appendChild(d);
        setTimeout(()=>d.remove(), 3000);
    }

    const rupiah = n => 'Rp ' + parseInt(n).toLocaleString('id-ID');

    // ===== NAVIGASI =====
    const titles = { dashboard:'Dashboard', customers:'Manajemen Pelanggan', products:'Manajemen Produk', purchases:'Pembelian / Restock', transactions:'Transaksi Penjualan', stock:'Manajemen Stok' };

    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const sec = link.dataset.section;
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            document.getElementById(`${sec}Section`).classList.add('active');
            document.querySelectorAll('.sidebar-menu a').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            document.getElementById('pageTitle').textContent = titles[sec] || '';
            if (sec==='dashboard')    loadStats();
            if (sec==='customers')    loadCustomers();
            if (sec==='products')     loadProducts();
            if (sec==='purchases')    loadPurchases();
            if (sec==='transactions') loadTransactions();
            if (sec==='stock')        loadStok();
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
        document.getElementById('totalCustomers').textContent   = d.total_pelanggan;
        document.getElementById('totalProducts').textContent    = d.total_produk;
        document.getElementById('totalTransactions').textContent = d.total_transaksi;
        document.getElementById('totalPurchases').textContent   = d.total_pembelian;
        if (d.produk_laris && d.produk_laris !== '-') {
            document.getElementById('produkLaris').textContent = d.produk_laris;
            document.getElementById('produkLarisCard').style.display = 'block';
        }
    }

    // ===== PELANGGAN =====
    async function loadCustomers() {
        const data = await apiGet('get_pelanggan');
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

    // ===== PRODUK =====
    async function loadProducts() {
        const data = await apiGet('get_produk');
        document.querySelector('#productsTable tbody').innerHTML = data.length === 0
            ? '<tr><td colspan="6" class="no-data">Tidak ada data produk</td></tr>'
            : data.map(p=>{
                const badge = p.stok>10?'success':(p.stok>0?'warning':'danger');
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

    function showProductModal() {
        document.getElementById('productModalTitle').textContent = 'Tambah Produk';
        ['productId','namaProduk','hargaProduk','stokProduk','jenisProduk'].forEach(id=>document.getElementById(id).value='');
        openModal('productModal');
    }

    function editProduct(id, nama, harga, stok, jenis) {
        document.getElementById('productModalTitle').textContent = 'Edit Produk';
        document.getElementById('productId').value   = id;
        document.getElementById('namaProduk').value  = decodeURIComponent(nama);
        document.getElementById('hargaProduk').value = harga;
        document.getElementById('stokProduk').value  = stok;
        document.getElementById('jenisProduk').value = decodeURIComponent(jenis);
        openModal('productModal');
    }

    async function saveProduct() {
        const id = document.getElementById('productId').value;
        const nama_produk  = document.getElementById('namaProduk').value;
        const harga        = parseFloat(document.getElementById('hargaProduk').value);
        const stok         = parseInt(document.getElementById('stokProduk').value);
        const jenis_produk = document.getElementById('jenisProduk').value;
        if (!nama_produk||!jenis_produk) { showAlert('Semua field wajib diisi','danger'); return; }
        const body = id ? {id_produk:+id,nama_produk,harga,stok,jenis_produk} : {nama_produk,harga,stok,jenis_produk};
        const res  = await apiPost(id?'update_produk':'add_produk', body);
        if (res.success) { showAlert(id?'Produk diperbarui':'Produk ditambahkan'); closeModal('productModal'); loadProducts(); }
        else showAlert(res.message||'Gagal','danger');
    }

    async function deleteProduct(id) {
        if (!confirm('Hapus produk ini?')) return;
        const res = await apiPost('delete_produk',{id});
        if (res.success) { showAlert('Produk dihapus'); loadProducts(); }
        else showAlert('Gagal','danger');
    }

    // ===== PEMBELIAN =====
    async function loadPurchases() {
        const data = await apiGet('get_pembelian');
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
        document.getElementById('produkPembelian').innerHTML = '<option value="">-- Pilih Produk --</option>' +
            produk.map(p=>`<option value="${p.id_produk}">${p.nama_produk} (Stok: ${p.stok})</option>`).join('');
        document.getElementById('tanggalPembelian').valueAsDate = new Date();
        ['jumlahPembelian','hargaBeli'].forEach(id=>document.getElementById(id).value='');
        document.getElementById('statusPembelian').value='';
        openModal('purchaseModal');
    }

    async function savePurchase() {
        const id_produk  = +document.getElementById('produkPembelian').value;
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
        const data = await apiGet('get_transaksi');
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
        document.getElementById('pelangganTransaksi').innerHTML = '<option value="">-- Pilih Pelanggan --</option>' +
            pelanggan.map(p=>`<option value="${p.id_pelanggan}">${p.nama}</option>`).join('');
        const produkSel = document.getElementById('produkTransaksi');
        produkSel.innerHTML = '<option value="">-- Pilih Produk --</option>' +
            produk.map(p=>`<option value="${p.id_produk}" data-harga="${p.harga}" data-stok="${p.stok}">${p.nama_produk} (Stok: ${p.stok})</option>`).join('');
        produkSel.onchange = () => {
            const opt = produkSel.options[produkSel.selectedIndex];
            document.getElementById('hargaTransaksi').value = opt.dataset.harga||'';
        };
        ['jumlahTransaksi','hargaTransaksi'].forEach(id=>document.getElementById(id).value='');
        openModal('transactionModal');
    }

    function addTransactionItem() {
        const sel    = document.getElementById('produkTransaksi');
        const opt    = sel.options[sel.selectedIndex];
        const jumlah = +document.getElementById('jumlahTransaksi').value;
        const harga  = +document.getElementById('hargaTransaksi').value;
        const stok   = +(opt.dataset.stok||0);
        if (!sel.value||!jumlah||!harga) { showAlert('Isi semua field item','warning'); return; }
        if (jumlah > stok) { showAlert('Stok tidak cukup','danger'); return; }
        transactionItems.push({ id_produk:+sel.value, nama:opt.text.split(' (')[0], jumlah, harga });
        renderTransactionItems();
        sel.value=''; document.getElementById('jumlahTransaksi').value=''; document.getElementById('hargaTransaksi').value='';
    }

    function renderTransactionItems() {
        let total = 0;
        document.getElementById('transactionItems').innerHTML = transactionItems.map((item,i)=>{
            const sub = item.jumlah * item.harga; total += sub;
            return `<div class="card" style="padding:12px;margin-bottom:8px;">
                <div class="flex" style="justify-content:space-between;align-items:center;">
                    <div><strong>${item.nama}</strong><br>${item.jumlah} x ${rupiah(item.harga)} = ${rupiah(sub)}</div>
                    <button type="button" class="btn btn-danger btn-small" onclick="removeItem(${i})">Hapus</button>
                </div></div>`;
        }).join('');
        document.getElementById('transactionGrandTotal').textContent = rupiah(total);
    }

    function removeItem(i) { transactionItems.splice(i,1); renderTransactionItems(); }

    async function saveTransaction() {
        const id_pelanggan = +document.getElementById('pelangganTransaksi').value;
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
            `<p><strong>ID Transaksi:</strong> ${id}</p><hr>
            <table class="table" style="width:100%"><thead><tr><th>Produk</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr></thead>
            <tbody>${rows}</tbody></table><hr>
            <h3 style="text-align:right">Grand Total: ${rupiah(grand)}</h3>`;
        openModal('detailsModal');
    }

    async function deleteTransaction(id) {
        if (!confirm('Hapus transaksi ini?')) return;
        const res = await apiPost('delete_transaksi',{id});
        if (res.success) { showAlert('Transaksi dihapus'); loadTransactions(); }
        else showAlert('Gagal','danger');
    }

    // ===== STOK =====
    async function loadStok() {
        const data = await apiGet('get_stok');
        document.querySelector('#stockTable tbody').innerHTML = data.length === 0
            ? '<tr><td colspan="4" class="no-data">Tidak ada data</td></tr>'
            : data.map(p=>{
                let badge='success', status='Baik';
                if (p.stok<=5) { badge='danger'; status='Kritis'; }
                else if (p.stok<=14) { badge='warning'; status='Rendah'; }
                return `<tr><td>${p.id_produk}</td><td>${p.nama_produk}</td>
                    <td><span class="badge badge-${badge}">${p.stok} unit</span></td>
                    <td>${status}</td></tr>`;
              }).join('');
    }

    // ===== INIT =====
    loadStats();
    </script>
</body>
</html>