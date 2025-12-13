-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 06:28 AM
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
-- Database: `bioskop`
--

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `sinopsis` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `poster` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `judul`, `sinopsis`, `harga`, `poster`) VALUES
(1, 'Avengers: Endgame', 'The Avengers assemble once more to reverse Thanos devastating actions.', 50000.00, 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg'),
(2, 'Spider-Man: No Way Home', 'Peter Parker seeks help from Doctor Strange.', 45000.00, 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_tayang`
--

CREATE TABLE `jadwal_tayang` (
  `id_penayangan` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `jadwal_penayangan` datetime NOT NULL,
  `jumlah_kursi_tersisah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_tayang`
--

INSERT INTO `jadwal_tayang` (`id_penayangan`, `id_film`, `jadwal_penayangan`, `jumlah_kursi_tersisah`) VALUES
(1, 1, '2025-12-10 14:00:00', 50),
(2, 1, '2025-12-10 19:00:00', 50),
(3, 2, '2025-12-11 16:30:00', 50);

-- --------------------------------------------------------

--
-- Table structure for table `kursi`
--

CREATE TABLE `kursi` (
  `id_kursi` int(11) NOT NULL,
  `id_penayangan` int(11) NOT NULL,
  `nomor_kursi` varchar(10) NOT NULL,
  `status` enum('tersedia','dipesan') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `kode_tiket` varchar(50) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_penayangan` int(11) NOT NULL,
  `kursi` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kursi`)),
  `total_harga` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('pending','berhasil','gagal') DEFAULT 'pending',
  `tanggal_pemesanan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id_promo` int(11) NOT NULL,
  `nama_promo` varchar(255) NOT NULL,
  `img_link_promo` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id_promo`, `nama_promo`, `img_link_promo`) VALUES
(1, 'Promo Tahun Baru', 'https://i.ytimg.com/vi/Z8O0pEBFVWY/hq720.jpg?sqp=-oaymwE7CK4FEIIDSFryq4qpAy0IARUAAAAAGAElAADIQj0AgKJD8AEB-AH-CYAC0AWKAgwIABABGDYgXyhlMA8=&rs=AOn4CLBaIcv_8Xg6lUjt2_qW6bnv2CIisg'),
(2, 'Diskon Weekend', 'https://web3.21cineplex.com/evoucher/WA2025091012595607736342193581_banner.jpg?sn=1029347334');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `foto_profil` varchar(255) DEFAULT 'default_avatar.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `role`, `foto_profil`, `created_at`) VALUES
(1, 'admin', 'admin@gmai.com', '$2y$10$GVdc2c295tZgWCEM3XhKfODVnxX.jMAci/FdOqHgjtrWx2oqY6DWO', 'admin', 'default_avatar.JPG', '2025-12-05 02:58:41'),
(2, 'Genzen', 'genzen23@gmail.com', '$2y$10$usJh/8mnE65eq.PTt0Ra9upEOBi/4/Uucu9GKEI0Bd.tlvemWMxVa', 'user', 'default_avatar.JPG', '2025-12-05 02:56:46'),
(3, 'iron4', 'iron@gmai.com', '$2y$10$4fhSR7fDRQFwOzacdQ4BmO5KHMt.YV61IKq7qRavcdL70b16s9h1O', 'user', 'default_avatar.JPG', '2025-12-05 03:44:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD PRIMARY KEY (`id_penayangan`),
  ADD KEY `id_film` (`id_film`);

--
-- Indexes for table `kursi`
--
ALTER TABLE `kursi`
  ADD PRIMARY KEY (`id_kursi`),
  ADD KEY `id_penayangan` (`id_penayangan`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD UNIQUE KEY `kode_tiket` (`kode_tiket`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_penayangan` (`id_penayangan`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id_promo`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  MODIFY `id_penayangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kursi`
--
ALTER TABLE `kursi`
  MODIFY `id_kursi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id_promo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_tayang`
--
ALTER TABLE `jadwal_tayang`
  ADD CONSTRAINT `jadwal_tayang_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kursi`
--
ALTER TABLE `kursi`
  ADD CONSTRAINT `kursi_ibfk_1` FOREIGN KEY (`id_penayangan`) REFERENCES `jadwal_tayang` (`id_penayangan`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_penayangan`) REFERENCES `jadwal_tayang` (`id_penayangan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
