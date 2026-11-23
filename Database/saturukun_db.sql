-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2026 at 04:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saturukun_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `ringkasan` text NOT NULL,
  `detail` longtext NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(255) DEFAULT 'RW 001 Garut',
  `gambar` varchar(255) DEFAULT 'default-berita.jpg',
  `status` enum('Dipublikasikan','Draft') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `ringkasan`, `detail`, `tanggal`, `lokasi`, `gambar`, `status`, `created_at`) VALUES
(1, 'Program Penghijauan RW 001 Dimulai', 'Warga memulai penanaman 100 bibit pohon di lingkungan RW.', 'Program penghijauan menjadi langkah bersama untuk menciptakan lingkungan yang lebih teduh, sehat, dan nyaman bagi seluruh warga RW 001 Garut.', '2026-01-10', 'RW 001 Garut', 'siluet-2-pohon.jpg', 'Dipublikasikan', '2026-10-23 02:07:58'),
(2, 'Jadwal Pelayanan Administrasi', 'Pelayanan surat pengantar tersedia setiap hari kerja.', 'Pelayanan administrasi dan surat pengantar warga dilaksanakan setiap hari Senin sampai Jumat pukul 08.00–15.00 WIB bertempat di Kantor RW 001 Garut.', '2026-01-08', 'RW 001 Garut', 'jalan.jpg', 'Dipublikasikan', '2026-10-23 02:07:58'),
(4, 'Kingdom Come Deliverance II', 'Game Baru Bagus Tapi Gagal GOTY', 'Mantap', '2025-06-17', 'Game Awards', 'berita_1792724203.png', 'Dipublikasikan', '2026-10-23 02:56:43');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `tempat` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT 'default-kegiatan.jpg',
  `status` enum('Dipublikasikan','Draft') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `judul`, `tanggal`, `tempat`, `deskripsi`, `gambar`, `status`, `created_at`) VALUES
(4, 'Kerja Bakti Lingkungan', '2026-01-12', 'Lapangan RW 001', 'Kegiatan kerja bakti bersama warga untuk menjaga kebersihan dan kenyamanan lingkungan RW 001 Garut.', 'kegiatan_1792720234.png', 'Dipublikasikan', '2026-10-23 01:41:13'),
(5, 'Posyandu Balita', '2026-01-18', 'Aula Warga', 'Pemeriksaan kesehatan dan tumbuh kembang balita di lingkungan RW 001.', 'jalan.jpg', 'Dipublikasikan', '2026-10-23 01:41:13'),
(6, 'Rapat Koordinasi Warga', '2026-01-25', 'Kantor RW', 'Rapat evaluasi program kerja dan pembahasan rencana kegiatan bulan depan.', 'siluet-2-pohon.jpg', 'Dipublikasikan', '2026-10-23 01:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int(11) NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keuangan`
--

INSERT INTO `keuangan` (`id`, `jenis`, `tanggal`, `keterangan`, `jumlah`, `created_at`) VALUES
(1, 'Pemasukan', '2026-10-12', 'Iuran warga Oktober', 4500000, '2026-10-23 03:04:55'),
(2, 'Pengeluaran', '2026-10-10', 'Perawatan fasilitas umum', 1250000, '2026-10-23 03:04:55'),
(3, 'Pemasukan', '2026-10-05', 'Donasi kegiatan warga', 1750000, '2026-10-23 03:04:55'),
(4, 'Pengeluaran', '2026-10-03', 'Perlengkapan kebersihan', 650000, '2026-10-23 03:04:55'),
(7, 'Pengeluaran', '2026-10-23', 'jajan anak', 200000, '2026-10-23 03:41:13'),
(8, 'Pengeluaran', '2026-10-23', 'jajajn anak', 1200000, '2026-10-23 06:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('Pending','Diproses','Selesai') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `aksi` varchar(100) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `tipe`, `aksi`, `detail`, `created_at`) VALUES
(1, 'berita', 'Berita Dipublikasikan', 'Program Penghijauan RW 001 Dimulai', '2026-10-23 03:53:46'),
(2, 'data_desa', 'Data Desa Diperbarui', 'Pembaruan total warga dan jumlah rumah', '2026-10-23 03:53:46'),
(3, 'profil_rw', 'Profil Ketua RW Diperbarui', 'Pembaruan kata sambutan Ketua RW', '2026-10-23 03:53:46'),
(4, 'keuangan', 'Transaksi Baru Dicatat', 'Iuran warga Oktober', '2026-10-23 03:53:46'),
(5, 'berita', 'Draft Berita Disimpan', 'Persiapan Bulan Bakti Gotong Royong', '2026-10-23 04:04:22'),
(6, 'data_desa', 'Data Desa Diperbarui', 'Total warga 500, rumah 130', '2026-10-23 04:18:28'),
(7, 'data_desa', 'Nama Ketua RW Diperbarui', 'Ketua RW: dr. Hj. Farih', '2026-10-23 04:18:40'),
(8, 'berita', 'Berita Dipublikasikan', 'Kingdom Come Deliverance II', '2026-10-23 05:44:05'),
(9, 'data_desa', 'Data Desa Diperbarui', 'Total warga 600, rumah 170', '2026-10-23 06:22:44'),
(10, 'data_desa', 'Nama Ketua RW Diperbarui', 'Ketua RW: dr. Hj. Farihh', '2026-10-23 06:22:54'),
(11, 'berita', 'Draft Berita Disimpan', 'Program Penghijauan RW 001 Dimulai', '2026-10-23 06:23:44'),
(12, 'berita', 'Berita Dipublikasikan', 'Program Penghijauan RW 001 Dimulai', '2026-10-23 06:23:52'),
(13, 'keuangan', 'Transaksi Pengeluaran', 'jajajn anak (Rp 1.200.000)', '2026-10-23 06:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `master_warga`
--

CREATE TABLE `master_warga` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_warga`
--

INSERT INTO `master_warga` (`id`, `nik`, `nama`) VALUES
(1, '3205011205900001', 'Admin RW 001'),
(2, '1234567890123456', 'Asep Kebul'),
(3, '1234567890123457', 'Ujang'),
(4, '1234567890123450', 'Indra');

-- --------------------------------------------------------

--
-- Table structure for table `profil_rw`
--

CREATE TABLE `profil_rw` (
  `id` int(11) NOT NULL DEFAULT 1,
  `nama` varchar(100) DEFAULT 'dr. Hj. Farih',
  `masa_jabatan` varchar(50) DEFAULT '2023 - 2028',
  `sambutan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default-rw.jpg',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil_rw`
--

INSERT INTO `profil_rw` (`id`, `nama`, `masa_jabatan`, `sambutan`, `foto`, `updated_at`) VALUES
(1, 'dr. Hj. Farih', '2023 - 2028', 'Kebersamaan adalah kekuatan kita untuk membangun lingkungan yang aman, nyaman, dan sejahtera bagi seluruh warga.', 'ketua_rw_1792719953.png', '2026-10-23 05:39:09');

-- --------------------------------------------------------

--
-- Table structure for table `statistik`
--

CREATE TABLE `statistik` (
  `id` int(11) NOT NULL DEFAULT 1,
  `jumlah_rumah` int(11) DEFAULT 128,
  `jumlah_warga` int(11) DEFAULT 436,
  `nama_ketua_rw` varchar(100) DEFAULT 'dr. Hj. Farih'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistik`
--

INSERT INTO `statistik` (`id`, `jumlah_rumah`, `jumlah_warga`, `nama_ketua_rw`) VALUES
(1, 170, 600, 'dr. Hj. Farihh');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('warga','admin') DEFAULT 'warga',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, '3205011205900001', 'Admin RW 001', 'komdigi@saturukun.com', 'admin123', 'admin', '2026-10-22 15:24:46'),
(2, '1234567890123456', 'Asep Kebul', 'asep@gmail.com', 'lol123', 'warga', '2026-10-22 17:08:34'),
(3, '1234567890123457', 'Ujang', 'ujang@gmail.com', 'lol123', 'warga', '2026-10-23 04:22:35'),
(4, '1234567890123450', 'Indra', 'Indra@gmail.com', 'lol123', 'warga', '2026-10-23 06:13:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_warga`
--
ALTER TABLE `master_warga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `profil_rw`
--
ALTER TABLE `profil_rw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `master_warga`
--
ALTER TABLE `master_warga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
