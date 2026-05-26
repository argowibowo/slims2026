-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 10:09 AM
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
-- Database: `rumah_sakit`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrian_pasien`
--

CREATE TABLE `antrian_pasien` (
  `id_antrian` int(11) NOT NULL,
  `no_rm` varchar(20) NOT NULL,
  `created_at` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farmasi`
--

CREATE TABLE `farmasi` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `sku` char(10) NOT NULL,
  `dosis` varchar(50) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmasi`
--

INSERT INTO `farmasi` (`id`, `nama_obat`, `sku`, `dosis`, `label`) VALUES
(1, 'paracetamol', 'PAR01', '10mg', 'Diminum sambil lari');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `id_rm` int(20) NOT NULL,
  `sku` char(10) DEFAULT NULL,
  `label_catatan` text DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `id_rm`, `sku`, `label_catatan`, `jumlah`) VALUES
(1, 1, 'PAR01', 'Diminum sebelum minum', 2),
(2, 0, '8972873248', 'diminum sebelum diminum', 2),
(3, 0, '8972873248', 'diminum sebelum diminum', 2),
(4, 0, NULL, 'diminum sebelum diminum', 2),
(5, 0, '8972873248', 'diminum sebelum diminum', 2),
(6, 0, '8972873248', 'diminum sebelum diminum', 2),
(7, 0, 'Smith', 'shdfusdf87s98df7ds', NULL),
(8, 0, 'Smith', 'shdfusdf87s98df7ds', NULL),
(9, 0, 'John', 'shdfusdf87s98df7ds', 123),
(10, 0, 'John', 'shdfusdf87s98df7ds', 123),
(11, 0, 'John', 'shdfusdf87s98df7ds', 123),
(12, 0, 'John', 'shdfusdf87s98df7ds', 123),
(13, 0, 'John', 'shdfusdf87s98df7ds', 123),
(14, 2147483647, '897987', '2x sehari', 5),
(15, 1434545, 'John', 'shdfusdf87s98df7ds', 123),
(16, 1434545, 'John', 'shdfusdf87s98df7ds', 123),
(17, 1434545, 'John', 'shdfusdf87s98df7ds', 123),
(18, 1434545, 'Argo', 'shdfusdf87s98df7ds', 123),
(19, 1434545, 'Jong Jek S', 'shdfusdf87s98df7ds', 123),
(20, 1434545, 'Argo', 'shdfusdf87s98df7ds', 123),
(21, 1434545, 'Argo', 'Sekali 3x', 123);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` char(13) DEFAULT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `no_rm` varchar(20) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `gol_darah` varchar(5) DEFAULT NULL,
  `tinggi` int(11) DEFAULT NULL,
  `berat` int(11) DEFAULT NULL,
  `kontak_keluarga` varchar(255) DEFAULT NULL,
  `kontak_keluarga_hp` char(13) DEFAULT NULL,
  `kontak_keluarga_alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_hp`, `jk`, `nik`, `no_rm`, `tgl_lahir`, `tempat_lahir`, `gol_darah`, `tinggi`, `berat`, `kontak_keluarga`, `kontak_keluarga_hp`, `kontak_keluarga_alamat`) VALUES
(1, 'Bram', 'Jogja', '098989', 'L', '088978678', '098837873', '2015-04-15', 'Jogja', 'A', 180, 80, 'Ayah', '089787', 'Jogja'),
(2, 'Bram 2', 'Jogja', '098989', 'L', '088978678', '098837873', '2015-04-15', 'Jogja', 'A', 180, 80, 'Ayah', '089787', 'Jogja'),
(3, 'Bram 3', 'Jogja', '098989', 'L', '088978678', '098837873', '2015-04-15', 'Jogja', 'A', 180, 80, 'Ayah', '089787', 'Jogja');

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id_rm` int(20) NOT NULL,
  `no_rm` varchar(20) NOT NULL,
  `keluhan` text DEFAULT NULL,
  `tinggi` int(11) DEFAULT NULL,
  `berat` int(11) DEFAULT NULL,
  `tensi` varchar(10) DEFAULT NULL,
  `dokter` varchar(255) DEFAULT NULL,
  `status_obat` varchar(255) DEFAULT NULL,
  `sku` char(10) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`id_rm`, `no_rm`, `keluhan`, `tinggi`, `berat`, `tensi`, `dokter`, `status_obat`, `sku`, `tanggal`) VALUES
(1, '098837873', 'Sakit Kepala Kronis', 180, 90, '120/80', 'Rowang Adi', 'selesai\r\n', 'PAR01', '2024-05-14'),
(2, '9283974', 'Asam lambung akut', 160, 58, '110', 'Freedy', 'selesai', 'PAR01', '2024-05-14'),
(3, '098837875', 'Sakit Hati Berat', 150, 50, '110/90', 'Tirta', 'menunggu', 'PAR01', '2024-05-14');

-- --------------------------------------------------------

--
-- Table structure for table `tindakan`
--

CREATE TABLE `tindakan` (
  `id` int(11) NOT NULL,
  `id_rm` int(11) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `no_rm` varchar(20) NOT NULL,
  `tindakan_no` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tindakan`
--

INSERT INTO `tindakan` (`id`, `id_rm`, `deskripsi`, `no_rm`, `tindakan_no`) VALUES
(1, 0, '-', '098837873', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrian_pasien`
--
ALTER TABLE `antrian_pasien`
  ADD PRIMARY KEY (`id_antrian`);

--
-- Indexes for table `farmasi`
--
ALTER TABLE `farmasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id_rm`);

--
-- Indexes for table `tindakan`
--
ALTER TABLE `tindakan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrian_pasien`
--
ALTER TABLE `antrian_pasien`
  MODIFY `id_antrian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farmasi`
--
ALTER TABLE `farmasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id_rm` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tindakan`
--
ALTER TABLE `tindakan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
