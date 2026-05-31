-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Bulan Mei 2026 pada 16.21
-- Versi server: 8.2.0
-- Versi PHP: 8.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `spazyan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_diagnosa`
--

CREATE TABLE `hasil_diagnosa` (
  `id_hasil` int NOT NULL,
  `id_pas` int NOT NULL,
  `id_penyakit` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `persentase` decimal(5,2) DEFAULT '0.00',
  `gejala_terpilih` text COLLATE utf8mb4_general_ci,
  `gejala_cocok` text COLLATE utf8mb4_general_ci,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_diagnosa`
--

INSERT INTO `hasil_diagnosa` (`id_hasil`, `id_pas`, `id_penyakit`, `persentase`, `gejala_terpilih`, `gejala_cocok`, `tanggal`) VALUES
(288, 11, 'K01', 100.00, 'G01,G02,G03', NULL, '2026-05-10 12:20:18'),
(289, 11, 'K02', 100.00, 'G04,G06,G05', NULL, '2026-05-10 12:21:19'),
(290, 11, 'K01', 100.00, 'G01,G02,G03', NULL, '2026-05-29 16:00:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_basis_aturan`
--

CREATE TABLE `tbl_basis_aturan` (
  `id_aturan` int NOT NULL,
  `id_penyakit` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `id_gejala` varchar(5) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_basis_aturan`
--

INSERT INTO `tbl_basis_aturan` (`id_aturan`, `id_penyakit`, `id_gejala`) VALUES
(1, 'K01', 'G01'),
(2, 'K01', 'G02'),
(3, 'K01', 'G03'),
(4, 'K02', 'G04'),
(5, 'K02', 'G05'),
(6, 'K02', 'G06'),
(7, 'K03', 'G07'),
(8, 'K03', 'G08'),
(9, 'K03', 'G09'),
(10, 'K03', 'G10'),
(11, 'K04', 'G11'),
(12, 'K04', 'G12'),
(13, 'K04', 'G13'),
(14, 'K05', 'G14'),
(15, 'K05', 'G15'),
(16, 'K05', 'G16'),
(17, 'K05', 'G17'),
(18, 'K06', 'G18'),
(19, 'K06', 'G19'),
(20, 'K06', 'G20'),
(21, 'K07', 'G21'),
(22, 'K07', 'G22'),
(23, 'K07', 'G23'),
(24, 'K07', 'G24'),
(25, 'K08', 'G07'),
(26, 'K08', 'G25'),
(27, 'K08', 'G26'),
(28, 'K09', 'G08'),
(29, 'K09', 'G27'),
(30, 'K09', 'G15'),
(31, 'K09', 'G28'),
(32, 'K10', 'G29'),
(33, 'K10', 'G14'),
(34, 'K10', 'G12'),
(35, 'K11', 'G30'),
(36, 'K11', 'G31'),
(37, 'K11', 'G32'),
(38, 'K11', 'G33'),
(39, 'K11', 'G34'),
(40, 'K12', 'G12'),
(41, 'K12', 'G35'),
(42, 'K12', 'G36'),
(43, 'K12', 'G07'),
(44, 'K12', 'G37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_gejala`
--

CREATE TABLE `tbl_gejala` (
  `id_gejala` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_gejala` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Lainnya'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_gejala`
--

INSERT INTO `tbl_gejala` (`id_gejala`, `nama_gejala`, `kategori`) VALUES
('G01', 'Mesin tidak bisa hidup & lampu mati total.', 'Kelistrikan'),
('G02', 'Klakson tidak bunyi', 'Kelistrikan'),
('G03', 'Speedometer mati', 'Kelistrikan'),
('G04', 'Starter lemah, lampu redup, klakson pelan', 'Kelistrikan'),
('G05', 'Motor sulit distarter terutama pagi hari', 'Mesin'),
('G06', 'Aki cepat habis meski baru diisi', 'Kelistrikan'),
('G07', 'Mesin brebet atau tersendat', 'Bahan Bakar'),
('G08', 'Mesin sulit hidup', 'Mesin'),
('G09', 'Mesin hidup sebentar lalu mati', 'Mesin'),
('G10', 'Knalpot meletup kecil', 'Mesin'),
('G11', 'Mesin tidak mendapat suplai bahan bakar', 'Bahan Bakar'),
('G12', 'Mesin mati mendadak saat jalan', 'Bahan Bakar'),
('G13', 'Suara pompa (fuel pump) tidak terdengar', 'Bahan Bakar'),
('G14', 'Mesin tersendat saat akselerasi', 'Bahan Bakar'),
('G15', 'Konsumsi bahan bakar boros', 'Bahan Bakar'),
('G16', 'Knalpot berasap hitam', 'Bahan Bakar'),
('G17', 'Mesin sering mati di idle (stasioner)', 'Bahan Bakar'),
('G18', 'Tidak ada percikan api di busi', 'Kelistrikan'),
('G19', 'Mesin mati total meski aki normal', 'Kelistrikan'),
('G20', 'Mesin hidup-mati tidak stabil', 'Mesin'),
('G21', 'Suara mesin kasar (ngelitik)', 'Mesin'),
('G22', 'Tenaga berkurang drastis', 'Mesin'),
('G23', 'Oli cepat habis', 'Mesin'),
('G24', 'Knalpot berasap putih/abu', 'Mesin'),
('G25', 'Tarikan terasa berat', 'Bahan Bakar'),
('G26', 'Mesin mati padahal indikator bensin masih ada', 'Bahan Bakar'),
('G27', 'Tenaga hilang', 'Mesin'),
('G28', 'Mesin cepat panas', 'Mesin'),
('G29', 'Mesin tidak stabil di RPM tinggi', 'Kelistrikan'),
('G30', 'Tarikan motor terasa berat (area CVT)', 'CVT'),
('G31', 'Akselerasi lambat meski gas penuh', 'CVT'),
('G32', 'Suara berisik dari area CVT', 'CVT'),
('G33', 'Motor bergetar saat mulai jalan', 'CVT'),
('G34', 'Kecepatan tidak stabil saat melaju', 'CVT'),
('G35', 'Tarikan motor tidak responsif', 'Bahan Bakar'),
('G36', 'RPM tidak stabil di idle (stasioner)', 'Bahan Bakar'),
('G37', 'Lampu indikator check engine menyala', 'Kelistrikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_penyakit`
--

CREATE TABLE `tbl_penyakit` (
  `id_penyakit` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_penyakit` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_penyakit`
--

INSERT INTO `tbl_penyakit` (`id_penyakit`, `nama_penyakit`) VALUES
('K01', 'Sekering'),
('K02', 'Aki'),
('K03', 'Busi'),
('K04', 'Fuel Pump'),
('K05', 'Injektor'),
('K06', 'Koil/Spul/ECM'),
('K07', 'Piston'),
('K08', 'Bahan Bakar'),
('K09', 'Bocor Kompresi'),
('K10', 'Pengapian'),
('K11', 'CVT (Continuously Variable Transmission)'),
('K12', 'Sensor TPS (Throttle Position Sensor)'),
('K13', '22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_solusi`
--

CREATE TABLE `tbl_solusi` (
  `id_solusi` int NOT NULL,
  `id_penyakit` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `solusi` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_solusi`
--

INSERT INTO `tbl_solusi` (`id_solusi`, `id_penyakit`, `solusi`) VALUES
(1, 'K01', 'Ganti sekering yang putus dengan ukuran yang sama..\'...'),
(2, 'K02', 'Isi ulang aki, cek sistem pengisian, ganti aki jika soak.'),
(3, 'K03', 'Bersihkan busi, setel celah busi, ganti busi jika sudah aus.'),
(4, 'K04', 'Periksa kabel pompa, bersihkan filter, ganti pompa jika rusak.'),
(5, 'K05', 'Bersihkan injektor dengan cairan khusus atau servis injektor.'),
(6, 'K06', 'Periksa kabel koil, ganti koil/spul/ECM bila rusak.'),
(7, 'K07', 'Bongkar mesin, ganti piston dan ring piston.'),
(8, 'K08', 'Bersihkan tangki, ganti filter bahan bakar, gunakan bahan bakar berkualitas.'),
(9, 'K09', 'Setel ulang klep, ganti gasket kepala silinder, lakukan overhoule jika perlu.'),
(10, 'K10', 'Periksa CDI, kabel pengapian, ganti komponen pengapian yang rusak.'),
(11, 'K11', 'Bersihkan CVT, cek kondisi roller dan v-belt, ganti jika aus, lumasi bagian yang perlu.'),
(12, 'K12', 'Bersihkan sensor TPS, cek konektor dan kabel, lakukan kalibrasi sensor, ganti sensor jika rusak.,,,'),
(13, 'K13', '11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jeniskelamin` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `username`, `jeniskelamin`, `email`, `password`, `role`) VALUES
(1, 'admin', '', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '1'),
(11, 'user', 'Laki-Laki', 'user@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', '2'),
(12, 'admin', 'Laki-Laki', 'yunus@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', '2'),
(14, 'admin', 'Laki-Laki', 'yunus@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '2');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `hasil_diagnosa`
--
ALTER TABLE `hasil_diagnosa`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indeks untuk tabel `tbl_basis_aturan`
--
ALTER TABLE `tbl_basis_aturan`
  ADD PRIMARY KEY (`id_aturan`);

--
-- Indeks untuk tabel `tbl_gejala`
--
ALTER TABLE `tbl_gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indeks untuk tabel `tbl_penyakit`
--
ALTER TABLE `tbl_penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- Indeks untuk tabel `tbl_solusi`
--
ALTER TABLE `tbl_solusi`
  ADD PRIMARY KEY (`id_solusi`),
  ADD KEY `id_penyakit` (`id_penyakit`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `hasil_diagnosa`
--
ALTER TABLE `hasil_diagnosa`
  MODIFY `id_hasil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT untuk tabel `tbl_basis_aturan`
--
ALTER TABLE `tbl_basis_aturan`
  MODIFY `id_aturan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `tbl_solusi`
--
ALTER TABLE `tbl_solusi`
  MODIFY `id_solusi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_solusi`
--
ALTER TABLE `tbl_solusi`
  ADD CONSTRAINT `fk_penyakit_solusi` FOREIGN KEY (`id_penyakit`) REFERENCES `tbl_penyakit` (`id_penyakit`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
