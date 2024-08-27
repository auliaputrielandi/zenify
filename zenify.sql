-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Waktu pembuatan: 26 Agu 2024 pada 06.45
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zenify`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `stres`
--

CREATE TABLE `stres` (
  `id_stres` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `depresi` int(2) NOT NULL,
  `kecemasan` int(2) NOT NULL,
  `stres` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stres`
--

INSERT INTO `stres` (`id_stres`, `id_user`, `depresi`, `kecemasan`, `stres`) VALUES
(1, 3, 9, 10, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(12) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `email`, `username`, `password`) VALUES
(3, '220514013@fellow.lpkia.ac.id', 'Aulia', '$2y$10$IxeZPNQyhrp3CmTON38zq.eQuFcfb9AhFrQVyKjmp.GumRLC4ZX2e');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wlb`
--

CREATE TABLE `wlb` (
  `id_wlb` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `satisfaction-balance` int(2) NOT NULL,
  `time-balance` int(2) NOT NULL,
  `involvement-balance` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wlb`
--

INSERT INTO `wlb` (`id_wlb`, `id_user`, `satisfaction-balance`, `time-balance`, `involvement-balance`) VALUES
(1, 3, 14, 14, 14);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `stres`
--
ALTER TABLE `stres`
  ADD PRIMARY KEY (`id_stres`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `wlb`
--
ALTER TABLE `wlb`
  ADD PRIMARY KEY (`id_wlb`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `stres`
--
ALTER TABLE `stres`
  MODIFY `id_stres` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `wlb`
--
ALTER TABLE `wlb`
  MODIFY `id_wlb` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
