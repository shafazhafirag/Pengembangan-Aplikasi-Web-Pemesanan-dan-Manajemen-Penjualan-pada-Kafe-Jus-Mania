-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jun 2025 pada 16.45
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jusmania`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan`
--

CREATE TABLE `bahan` (
  `id` int(11) NOT NULL,
  `nama_bahan` varchar(100) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `satuan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bahan`
--

INSERT INTO `bahan` (`id`, `nama_bahan`, `jumlah`, `satuan`) VALUES
(1, 'Alpukat', '12.00', 'buah'),
(5, 'Gula', '12000.00', 'gram'),
(6, 'Es', '12000.00', 'gram'),
(7, 'Mangga', '34.00', 'buah'),
(8, 'Sirsak', '11.00', 'buah'),
(9, 'jeruk', '12.00', 'buah'),
(11, 'Pisang', '31.00', 'buah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `total_bayar` decimal(12,2) NOT NULL,
  `waktu_bayar` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `pakai_es` tinyint(1) DEFAULT 0,
  `pakai_gula` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menujus`
--

CREATE TABLE `menujus` (
  `id` int(11) NOT NULL,
  `nama_jus` varchar(100) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `menujus`
--

INSERT INTO `menujus` (`id`, `nama_jus`, `id_bahan`, `harga`, `deskripsi`) VALUES
(1, 'Jus Alpukat', 1, '6000.00', 'Jus alpukat memiliki berbagai khasiat yang baik untuk kesehatan, seperti menjaga kesehatan jantung, mendukung pencernaan, meningkatkan kesehatan mata, dan membantu menjaga berat badan. '),
(2, 'Jus Mangga', 7, '5000.00', 'Jus mangga mengandung banyak manfaat untuk kesehatan, terutama karena kandungan vitamin, antioksidan, dan seratnya yang tinggi.'),
(3, 'Jus Sirsak', 8, '8000.00', 'Jus sirsak menawarkan berbagai manfaat bagi kesehatan, termasuk meningkatkan sistem imun, melancarkan pencernaan, menjaga kesehatan jantung, dan mengontrol tekanan darah.'),
(4, 'Jus Jeruk', 9, '5000.00', 'Jus jeruk kaya akan vitamin C, antioksidan, dan nutrisi penting lainnya, sehingga memberikan banyak manfaat kesehatan, seperti meningkatkan daya tahan tubuh, menjaga kesehatan kulit, mencegah batu ginjal, dan menjaga kesehatan jantung'),
(7, 'Jus Pisang', 11, '9000.00', 'Minum jus pisang juga bisa menambah energi tubuh karena kandungan karbohidratnya yang tinggi. Selain itu, pisang juga mengandung kalium dan magnesium yang berperan sebagai elektrolit. ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_update_ad` int(11) NOT NULL,
  `waktu_pesan` datetime NOT NULL DEFAULT current_timestamp(),
  `pakai_es` tinyint(4) DEFAULT 0,
  `pakai_gula` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id`, `nama`) VALUES
(1, 'dapur'),
(2, 'kasir'),
(3, 'pelanggan'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `update_ad`
--

CREATE TABLE `update_ad` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `update_ad`
--

INSERT INTO `update_ad` (`id`, `status`) VALUES
(1, 'unpaid'),
(2, 'paid'),
(3, 'diproses'),
(4, 'selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(32) NOT NULL,
  `idrole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `idrole`) VALUES
(1, 'admin', 'admin01', '25d55ad283aa400af464c76d713c07ad', 4),
(2, 'Rorona Zoro', 'zoro01', '25d55ad283aa400af464c76d713c07ad', 1),
(3, 'Ritsuki Chan', 'ritsuki01', '25d55ad283aa400af464c76d713c07ad', 2),
(4, 'vivi', 'vivi01', '25d55ad283aa400af464c76d713c07ad', 3),
(10, 'Shafa', 'sapa', 'e8dc4081b13434b45189a720b77b6818', 3);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nama_bahan` (`nama_bahan`);

--
-- Indeks untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `menujus`
--
ALTER TABLE `menujus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nama_jus` (`nama_jus`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `id_update_ad` (`id_update_ad`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `update_ad`
--
ALTER TABLE `update_ad`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idrole` (`idrole`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `menujus`
--
ALTER TABLE `menujus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `update_ad`
--
ALTER TABLE `update_ad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`);

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menujus` (`id`);

--
-- Ketidakleluasaan untuk tabel `menujus`
--
ALTER TABLE `menujus`
  ADD CONSTRAINT `menujus_ibfk_1` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menujus` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`id_update_ad`) REFERENCES `update_ad` (`id`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`idrole`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
