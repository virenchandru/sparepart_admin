<!-- ====== PELANGGAN ====== -->
<div id="customersSection" class="content-section">
    <div class="section-header">
        <h2>Manajemen Pelanggan</h2>
        <button class="btn btn-primary" onclick="showCustomerModal()">+ Tambah Pelanggan</button>
    </div>
    <div class="filter-bar">
        <div class="search-box">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" id="searchCustomer" placeholder="Cari nama / no HP..." oninput="filterCustomers()">
        </div>
        <div class="searchable-select" id="alamatSearchWrapper" style="max-width: 280px; min-width: 200px;">
            <div class="search-box" style="margin:0;">
                <i data-lucide="map-pin" class="search-icon"></i>
                <input type="text" class="searchable-input" id="filterCustomerAlamat" placeholder="Filter alamat... (cth: Tangerang)" autocomplete="off" oninput="onAlamatInput()" onfocus="onAlamatFocus()" style="padding-left:40px;">
            </div>
            <div class="searchable-dropdown" id="alamatDropdown"></div>
        </div>
        <select class="filter-select" id="sortCustomer" onchange="filterCustomers()">
            <option value="default">Urutkan: Default</option>
            <option value="az">Nama A - Z</option>
            <option value="za">Nama Z - A</option>
        </select>
    </div>
    <div class="table-container">
        <table class="table" id="customersTable">
            <thead><tr><th>ID</th><th>Nama</th><th>No HP</th><th>Alamat</th><th>Aksi</th></tr></thead>
            <tbody></tbody>
        </table>
    </div>
</div>
