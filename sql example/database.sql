-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Host: localhost
-- Waktu pembuatan: 21 Mei 2026
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database creation skipped for shared hosting compatibility

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `username`, `password`) VALUES
(1, 'Administrator', 'admin', 'admin'),
(2, 'Kasir 1', 'kasir1', 'kasir123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `no_hp`, `alamat`) VALUES
(1, 'Budi Santoso', '081234567890', 'Jl. Merdeka No. 10, Jakarta'),
(2, 'Siti Aminah', '085678901234', 'Jl. Sudirman No. 25, Bandung'),
(3, 'Andi Wijaya', '081987654321', 'Jl. Diponegoro No. 5, Surabaya'),
(4, 'Rina Permata', '081345678912', 'Jl. Pahlawan No. 8, Semarang'),
(5, 'Agus Setiawan', '082156789012', 'Jl. Gajah Mada No. 12, Yogyakarta'),
(6, 'Dewi Lestari', '085712345678', 'Jl. Imam Bonjol No. 3, Medan'),
(7, 'Joko Purwanto', '081823456789', 'Jl. Ahmad Yani No. 15, Makassar'),
(8, 'Ratna Sari', '082234567890', 'Jl. Hasanuddin No. 7, Denpasar'),
(9, 'Hendra Gunawan', '081198765432', 'Jl. Veteran No. 22, Palembang'),
(10, 'Maya Indah', '085812349876', 'Jl. S. Parman No. 9, Balikpapan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `jenis_produk` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `stok`, `jenis_produk`) VALUES
(1, 'Kampas Rem Depan Honda', 45000.00, 50, 'Kampas Rem'),
(2, 'Kampas Rem Belakang Honda', 40000.00, 45, 'Kampas Rem'),
(3, 'Kampas Rem Depan Yamaha', 48000.00, 30, 'Kampas Rem'),
(4, 'Kampas Rem Belakang Yamaha', 42000.00, 35, 'Kampas Rem'),
(5, 'Busi NGK C7HSA', 15000.00, 100, 'Busi'),
(6, 'Busi Denso U22FS-U', 16000.00, 80, 'Busi'),
(7, 'Oli Mesin Yamalube 0.8L', 45000.00, 60, 'Oli'),
(8, 'Oli Mesin AHM MPX1 0.8L', 50000.00, 70, 'Oli'),
(9, 'Oli Gardan Yamalube 100ml', 15000.00, 50, 'Oli'),
(10, 'Oli Gardan AHM 120ml', 16000.00, 65, 'Oli'),
(11, 'V-Belt Mio J / GT', 85000.00, 20, 'V-Belt'),
(12, 'V-Belt Beat FI', 90000.00, 25, 'V-Belt'),
(13, 'Roller Vario 125 (Set)', 45000.00, 40, 'Roller'),
(14, 'Roller NMAX (Set)', 55000.00, 30, 'Roller'),
(15, 'Filter Udara Vario 150', 35000.00, 50, 'Filter'),
(16, 'Filter Udara Beat FI', 32000.00, 60, 'Filter'),
(17, 'Rantai Keteng Vixion', 120000.00, 15, 'Rantai'),
(18, 'Gear Set Supra X 125', 180000.00, 20, 'Gear Set'),
(19, 'Gear Set CBR 150R', 250000.00, 10, 'Gear Set'),
(20, 'Aki Yuasa YTZ5S', 185000.00, 25, 'Aki'),
(21, 'Aki GS Astra GTZ4V', 190000.00, 30, 'Aki'),
(22, 'Bohlam Depan Osram 35W', 25000.00, 50, 'Lampu'),
(23, 'Bohlam Belakang Philips', 15000.00, 40, 'Lampu'),
(24, 'Ban Depan IRC 80/90-14', 150000.00, 20, 'Ban'),
(25, 'Ban Belakang IRC 90/90-14', 180000.00, 20, 'Ban'),
(26, 'Ban Depan Michelin 90/80-14', 280000.00, 10, 'Ban'),
(27, 'Ban Belakang Michelin 100/80-14', 320000.00, 10, 'Ban'),
(28, 'Kaca Spion Kanan Honda', 35000.00, 30, 'Aksesoris'),
(29, 'Kaca Spion Kiri Honda', 35000.00, 30, 'Aksesoris'),
(30, 'Kaca Spion Kanan Yamaha', 38000.00, 25, 'Aksesoris'),
(31, 'Kabel Gas Vario', 35000.00, 20, 'Kabel'),
(32, 'Kabel Rem Supra', 25000.00, 30, 'Kabel'),
(33, 'Kabel Kopling Vixion', 40000.00, 20, 'Kabel'),
(34, 'Bearing Roda Depan 6201', 20000.00, 50, 'Bearing'),
(35, 'Bearing Roda Belakang 6301', 25000.00, 40, 'Bearing'),
(36, 'Kampas Kopling Vixion (Set)', 150000.00, 15, 'Kampas Kopling'),
(37, 'Shockbreaker YSS Matic', 450000.00, 10, 'Suspensi'),
(38, 'Shockbreaker KTC', 850000.00, 5, 'Suspensi'),
(39, 'Master Rem Depan Kanan', 95000.00, 15, 'Pengereman'),
(40, 'Handle Rem Kiri', 25000.00, 30, 'Aksesoris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_produk`, `tanggal`, `jumlah`, `harga_beli`, `status`, `id_admin`) VALUES
(1, 1, '2026-05-01', 20, 35000.00, 'diterima', 1),
(2, 7, '2026-05-05', 50, 38000.00, 'diterima', 1),
(3, 20, '2026-05-10', 10, 160000.00, 'pending', 1),
(4, 24, '2026-05-15', 5, 130000.00, 'dibatalkan', 2),
(5, 15, '2026-05-20', 30, 28000.00, 'diterima', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `id_pelanggan`, `id_admin`) VALUES
(1, '2026-05-02', 1, 1),
(2, '2026-05-06', 3, 2),
(3, '2026-05-11', 5, 1),
(4, '2026-05-16', 2, 2),
(5, '2026-05-21', 10, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `harga`) VALUES
(1, 1, 1, 1, 45000.00),
(2, 1, 8, 2, 50000.00),
(3, 2, 7, 1, 45000.00),
(4, 2, 11, 1, 85000.00),
(5, 3, 20, 1, 185000.00),
(6, 4, 18, 1, 180000.00),
(7, 4, 34, 2, 20000.00),
(8, 5, 5, 1, 15000.00),
(9, 5, 22, 1, 25000.00);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `pembelian_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
