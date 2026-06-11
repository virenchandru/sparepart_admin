<!-- MODAL PELANGGAN -->
<div id="customerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span id="customerModalTitle">Tambah Pelanggan</span>
            <button class="close-btn" onclick="closeModal('customerModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="customerId">
            <div class="form-group"><label>Nama</label><input type="text" id="namaCustomer" placeholder="Masukkan nama pelanggan"></div>
            <div class="form-group"><label>No HP</label><input type="text" id="noHpCustomer" placeholder="Masukkan nomor HP"></div>
            <div class="form-group"><label>Alamat</label><textarea id="alamatCustomer" rows="3" placeholder="Masukkan alamat lengkap"></textarea></div>
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
            <div class="form-group"><label>Nama Produk</label><input type="text" id="namaProduk" placeholder="Nama produk"></div>
            <div class="form-group"><label>Harga</label><input type="number" id="hargaProduk" min="0" placeholder="0"></div>
            <div class="form-group"><label>Stok</label><input type="number" id="stokProduk" min="0" placeholder="0"></div>
            <div class="form-group"><label>Jenis Produk</label><input type="text" id="jenisProduk" placeholder="Kategori produk"></div>
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
                <div class="searchable-select" id="produkPembelianSearchWrapper">
                    <input type="text" class="searchable-input" id="produkPembelianSearchInput" placeholder="Ketik nama produk..." autocomplete="off">
                    <input type="hidden" id="produkPembelianValue">
                    <div class="searchable-dropdown" id="produkPembelianDropdown"></div>
                </div>
            </div>
            <div class="form-group"><label>Jumlah</label><input type="number" id="jumlahPembelian" min="1" placeholder="0"></div>
            <div class="form-group"><label>Harga Beli (per unit)</label><input type="number" id="hargaBeli" min="0" placeholder="0"></div>
            <div class="form-group"><label>Status</label>
                <select id="statusPembelian">
                    <option value="">-- Pilih Status --</option>
                    <option value="diproses">Diproses</option>
                    <option value="diterima">Diterima</option>
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
                    <option value="diproses">Diproses</option>
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
                <div class="searchable-select" id="pelangganSearchWrapper">
                    <input type="text" class="searchable-input" id="pelangganSearchInput" placeholder="Ketik nama pelanggan..." autocomplete="off">
                    <input type="hidden" id="pelangganTransaksiValue">
                    <div class="searchable-dropdown" id="pelangganDropdown"></div>
                </div>
            </div>
            <hr>
            <h4>Tambah Item</h4>
            <div class="form-group"><label>Produk</label>
                <div class="searchable-select" id="produkSearchWrapper">
                    <input type="text" class="searchable-input" id="produkSearchInput" placeholder="Ketik nama produk..." autocomplete="off">
                    <input type="hidden" id="produkTransaksiValue">
                    <div class="searchable-dropdown" id="produkDropdown"></div>
                </div>
            </div>
            <div class="form-group"><label>Jumlah</label><input type="number" id="jumlahTransaksi" min="1" placeholder="0"></div>
            <div class="form-group"><label>Harga (per unit)</label><input type="number" id="hargaTransaksi" min="0" placeholder="0"></div>
            <button type="button" class="btn btn-secondary" onclick="addTransactionItem()">+ Tambah Item</button>
            <hr>
            <h4>Item Transaksi</h4>
            <div id="transactionItems"></div>
            <h3 style="text-align:right;margin-top:15px;">Grand Total: <span id="transactionGrandTotal" style="color:var(--accent);">Rp 0</span></h3>
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
