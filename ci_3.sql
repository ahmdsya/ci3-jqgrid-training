-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 18 Nov 2021 pada 22.44
-- Versi server: 5.7.31
-- Versi PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci_3`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(225) NOT NULL,
  `nik` varchar(225) NOT NULL,
  `hp` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `alamat` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `nik`, `hp`, `email`, `alamat`) VALUES
(1, 'Ahmad Syarif', '1234567890', '08123456789', 'master@mail.com', 'Medan'),
(3, 'Muhammad', '987654321', '1234567890', 'test@mail.com', 'Medan'),
(14, 'AAA', '123456', '0987612345', 'aaa@mail.com', 'Medan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
CREATE TABLE IF NOT EXISTS `pesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pelanggan_id` int(11) NOT NULL,
  `nama_produk` varchar(225) NOT NULL,
  `harga` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `pelanggan_id`, `nama_produk`, `harga`, `qty`, `total_harga`) VALUES
(1, 1, 'Spidol', 4000, 2, 8000),
(2, 1, 'Pencil', 2000, 4, 8000),
(3, 3, 'Buku', 5000, 2, 10000),
(4, 3, 'Tas', 10000, 1, 10000),
(5, 14, 'Sepatu', 20000, 3, 60000),
(6, 14, 'Jam Tangan', 15000, 1, 15000),
(7, 14, 'Buku', 3000, 5, 15000);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
