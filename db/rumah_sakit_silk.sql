-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 08:48 AM
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
-- Database: `rumah_sakit_silk`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrian`
--

CREATE TABLE `antrian` (
  `id_antrian` int(11) NOT NULL,
  `id_rm` int(11) DEFAULT NULL,
  `id_staff` int(11) DEFAULT NULL,
  `id_poli` int(11) DEFAULT NULL,
  `jenis_antrian` varchar(25) DEFAULT NULL,
  `nomor_antrian` int(11) DEFAULT NULL,
  `status_antrian` varchar(25) DEFAULT NULL,
  `waktu_dilayani` date DEFAULT NULL,
  `waktu_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antrian`
--

INSERT INTO `antrian` (`id_antrian`, `id_rm`, `id_staff`, `id_poli`, `jenis_antrian`, `nomor_antrian`, `status_antrian`, `waktu_dilayani`, `waktu_selesai`) VALUES
(1, 1, 2, 1, 'Kunjungan Baru', 1, 'Selesai', '2025-10-09', '2025-10-09'),
(2, 2, 2, 3, 'Kunjungan Baru', 2, 'Selesai', '2025-10-09', '2025-10-09'),
(3, 3, 2, 1, 'Kunjungan Lama', 3, 'Menunggu', NULL, NULL),
(4, 7, 2, 3, 'Kunjungan Baru', 4, 'Menunggu', '2025-11-11', '2025-11-12'),
(5, 7, 2, 3, 'Kunjungan Baru', 5, 'Dipanggil', '2025-11-11', '2025-11-12');

-- --------------------------------------------------------

--
-- Table structure for table `detil_resep`
--

CREATE TABLE `detil_resep` (
  `id_detil_resep` int(11) NOT NULL,
  `id_resep` int(11) DEFAULT NULL,
  `id_obat` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `dosis` varchar(30) DEFAULT NULL,
  `label` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detil_resep`
--

INSERT INTO `detil_resep` (`id_detil_resep`, `id_resep`, `id_obat`, `jumlah`, `dosis`, `label`) VALUES
(1, 1, 1, 10, '1x sehari 1 tablet', 'Diminum sesudah makan'),
(2, 2, 2, 15, '3x sehari 1 kapsul', 'Diminum sebelum makan'),
(3, 2, 3, 5, 'Jika sesak, 1 tablet', 'Obat hirup darurat');

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `nama_dokter` varchar(100) DEFAULT NULL,
  `spesialisasi` varchar(50) DEFAULT NULL,
  `id_poli` int(11) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `nama_dokter`, `spesialisasi`, `id_poli`, `no_telp`, `alamat`) VALUES
(1, 'Dr. Andi Wijaya', 'Umum', 1, '08112345678', 'Jl. Sudirman No. 10, Jakarta'),
(2, 'Drg. Dewi Sinta', 'Gigi', 2, '08123456789', 'Jl. Thamrin No. 20, Jakarta'),
(3, 'Dr. Tania Putri, Sp. A', 'Anak', 3, '08134567890', 'Jl. Gatot Subroto No. 30, Jakarta');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `sku` varchar(10) DEFAULT NULL,
  `nama_obat` varchar(100) DEFAULT NULL,
  `jenis_obat` varchar(50) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `tanggal_kadaluarsa` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id_obat`, `sku`, `nama_obat`, `jenis_obat`, `satuan`, `stok`, `tanggal_kadaluarsa`) VALUES
(1, 'ABC001', 'Paracetamol 500mg', 'Analgesik', 'Tablet', 500, '2026-10-01'),
(2, 'XYZ005', 'Amoxicillin 250mg', 'Antibiotik', 'Kapsul', 350, '2025-12-15'),
(3, 'DEF010', 'Salbutamol 2mg', 'Bronkodilator', 'Tablet', 200, '2027-05-20');

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id_rm` int(11) NOT NULL,
  `nik` int(11) DEFAULT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `jenis_kelamin` char(1) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `status_pasien` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id_rm`, `nik`, `nama_pasien`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_telp`, `status_pasien`) VALUES
(1, 321001, 'Ahmad Fauzi', 'L', '1990-05-15', 'Jl. Pahlawan No. 1, Jakarta Timur', '085111222333', 'Umum'),
(2, 321002, 'Bella Cantika', 'P', '2018-11-20', 'Jl. Raya Bogor No. 25, Jakarta Timur', '085444555666', 'BPJS'),
(3, 321003, 'Candra Jaya', 'L', '1975-03-01', 'Jl. Menteng No. 10, Jakarta Pusat', '085777888999', 'Umum'),
(4, NULL, 'Dave Aryanda Agape', NULL, NULL, 'Jl. Tangerang No. 9, Tangerang Utara', NULL, NULL),
(5, NULL, 'Gatot Subroto Simbolon', NULL, NULL, 'Jl. Dr. Wahidin No. 3, Yogyakarta', NULL, NULL),
(6, NULL, 'Mario Kevin Kristianto', NULL, NULL, 'Jl. Jambon No 1000, Yogyakarta', NULL, NULL),
(7, 2147483647, 'Stevanus Deden', 'L', '2002-02-03', 'Jl. Hijau No 3', '08126421055', 'BPJS');

-- --------------------------------------------------------

--
-- Table structure for table `poli`
--

CREATE TABLE `poli` (
  `id_poli` int(11) NOT NULL,
  `nama_poli` varchar(150) DEFAULT NULL,
  `lokasi` text DEFAULT NULL,
  `no_Telp` varchar(15) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poli`
--

INSERT INTO `poli` (`id_poli`, `nama_poli`, `lokasi`, `no_Telp`, `keterangan`) VALUES
(1, 'Poli Umum', 'Gedung A, Lantai 1', '02112345601', 'Pelayanan kesehatan dasar dan pemeriksaan rutin'),
(2, 'Poli Gigi', 'Gedung B, Lantai 2', '02112345602', 'Perawatan dan pengobatan gigi serta mulut'),
(3, 'Poli Anak', 'Gedung A, Lantai 2', '02112345603', 'Khusus pasien anak dan imunisasi');

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `id_resep` int(11) NOT NULL,
  `id_pasien` int(11) DEFAULT NULL,
  `id_dokter` int(11) DEFAULT NULL,
  `tanggal_resep` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resep`
--

INSERT INTO `resep` (`id_resep`, `id_pasien`, `id_dokter`, `tanggal_resep`, `keterangan`) VALUES
(1, 1, 1, '2025-10-09', 'Resep untuk gejala flu dan demam pasien Ahmad Fauzi'),
(2, 2, 3, '2025-10-09', 'Resep untuk batuk pilek anak pasien Bella Cantika');

-- --------------------------------------------------------

--
-- Table structure for table `rm`
--

CREATE TABLE `rm` (
  `id_rm_detil` int(11) NOT NULL,
  `id_rm` int(11) DEFAULT NULL,
  `id_dokter` int(11) DEFAULT NULL,
  `id_poli` int(11) DEFAULT NULL,
  `id_resep` int(11) DEFAULT NULL,
  `tgl_kunjungan` date DEFAULT NULL,
  `keluhan` text DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `rencana_tindak_lanjut` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `tekanan_darah` varchar(20) DEFAULT NULL,
  `suhu_tubuh` decimal(3,1) DEFAULT NULL,
  `berat_badan` decimal(4,1) DEFAULT NULL,
  `tinggi_badan` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rm`
--

INSERT INTO `rm` (`id_rm_detil`, `id_rm`, `id_dokter`, `id_poli`, `id_resep`, `tgl_kunjungan`, `keluhan`, `diagnosa`, `tindakan`, `rencana_tindak_lanjut`, `status`, `tekanan_darah`, `suhu_tubuh`, `berat_badan`, `tinggi_badan`, `catatan`) VALUES
(1, 1, 1, 1, 1, '2025-10-09', 'Demam 2 hari, nyeri tenggorokan', 'Influenza A', 'Anamnesa, pemeriksaan fisik, pemberian resep', 'Kontrol dalam 3 hari jika gejala tidak membaik', 'Selesai', '120/80 mmHg', 38.5, 70.2, 175, 'Pasien disarankan istirahat total'),
(2, 2, 3, 3, 2, '2025-10-09', 'Batuk berdahak dan sedikit sesak sejak semalam', 'Bronchitis akut', 'Pemeriksaan fisik anak, nebulisasi, pemberian resep', 'Istirahat dan obat rutin, awasi tanda bahaya', 'Selesai', NULL, 37.0, 18.5, 110, 'Orang tua diberikan edukasi tentang obat darurat');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id_staff` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id_staff`, `nama`, `alamat`, `role`) VALUES
(1, 'Rina Melati', 'Jl. Kenanga No. 5, Jakarta', 'Perawat'),
(2, 'Budi Hartono', 'Jl. Merdeka No. 12, Jakarta', 'Administrasi'),
(3, 'Siti Aisyah', 'Jl. Mawar No. 8, Jakarta', 'Apoteker');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrian`
--
ALTER TABLE `antrian`
  ADD PRIMARY KEY (`id_antrian`),
  ADD KEY `id_rm` (`id_rm`),
  ADD KEY `id_staff` (`id_staff`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indexes for table `detil_resep`
--
ALTER TABLE `detil_resep`
  ADD PRIMARY KEY (`id_detil_resep`),
  ADD KEY `id_resep` (`id_resep`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_rm`);

--
-- Indexes for table `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id_poli`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`id_resep`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_dokter` (`id_dokter`);

--
-- Indexes for table `rm`
--
ALTER TABLE `rm`
  ADD PRIMARY KEY (`id_rm_detil`),
  ADD KEY `id_rm` (`id_rm`),
  ADD KEY `id_dokter` (`id_dokter`),
  ADD KEY `id_poli` (`id_poli`),
  ADD KEY `rm_ibfk_4` (`id_resep`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id_staff`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id_antrian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detil_resep`
--
ALTER TABLE `detil_resep`
  MODIFY `id_detil_resep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_rm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `poli`
--
ALTER TABLE `poli`
  MODIFY `id_poli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `id_resep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rm`
--
ALTER TABLE `rm`
  MODIFY `id_rm_detil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antrian`
--
ALTER TABLE `antrian`
  ADD CONSTRAINT `antrian_ibfk_1` FOREIGN KEY (`id_rm`) REFERENCES `pasien` (`id_rm`),
  ADD CONSTRAINT `antrian_ibfk_2` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`),
  ADD CONSTRAINT `antrian_ibfk_3` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id_poli`);

--
-- Constraints for table `detil_resep`
--
ALTER TABLE `detil_resep`
  ADD CONSTRAINT `detil_resep_ibfk_1` FOREIGN KEY (`id_resep`) REFERENCES `resep` (`id_resep`),
  ADD CONSTRAINT `detil_resep_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`);

--
-- Constraints for table `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id_poli`);

--
-- Constraints for table `resep`
--
ALTER TABLE `resep`
  ADD CONSTRAINT `resep_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_rm`),
  ADD CONSTRAINT `resep_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`);

--
-- Constraints for table `rm`
--
ALTER TABLE `rm`
  ADD CONSTRAINT `rm_ibfk_1` FOREIGN KEY (`id_rm`) REFERENCES `pasien` (`id_rm`),
  ADD CONSTRAINT `rm_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  ADD CONSTRAINT `rm_ibfk_3` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id_poli`),
  ADD CONSTRAINT `rm_ibfk_4` FOREIGN KEY (`id_resep`) REFERENCES `resep` (`id_resep`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
