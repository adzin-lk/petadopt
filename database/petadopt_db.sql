-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jul 2026 pada 09.01
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
-- Database: `petadopt_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$m0LiSDk4kJwumm5mYlqpB.n7zpZq6V/dwlmGGPKLc4lHal/uhHHP2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hewan`
--

CREATE TABLE `hewan` (
  `id_hewan` int(11) NOT NULL,
  `nama_hewan` varchar(50) NOT NULL,
  `jenis_hewan` enum('Kucing','Anjing','Kelinci','Lainnya') NOT NULL,
  `ras` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) NOT NULL,
  `status_adopsi` enum('Tersedia','Diadopsi') DEFAULT 'Tersedia',
  `lokasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hewan`
--

INSERT INTO `hewan` (`id_hewan`, `nama_hewan`, `jenis_hewan`, `ras`, `tanggal_lahir`, `tanggal_masuk`, `deskripsi`, `foto`, `status_adopsi`, `lokasi`, `created_at`) VALUES
(6, 'moli', 'Kucing', 'KITTEN PERSIA MEDIUM MIX BSH', '2025-12-31', '2026-07-13', 'Jantan, usia 2.5 bulan, super aktif, gembul, pup pee sudah mandiri, bulu kapas, sudah minum obat cacing, dan treatment anti kutu jamur scabies setiap pagi.\r\n\r\nHARGA :  1,000,000', '6a57e4a5e9b5d.jpg', 'Tersedia', 'Yogyakarta', '2026-07-14 09:02:46'),
(7, 'loli', 'Anjing', 'poddle', '2025-01-07', '2026-07-12', 'Ini adalah anak poodle peliharaan kesayangan kami..di adopt karna kami tidak mau banyak2 dogie..\r\nHanya mahar biaya lahiran saja 1.7jt.\r\nPoodle dalam kondisi sangat sehat, tanpa kutu, bersih dan bebas penyakit kulit..sangat terawat dan makan makanan yang bergizi sehingga pintar dan aktve..\r\n\r\n\r\nHARGA : Rp 1.700.000', '6a57e414a692a.jpg', 'Tersedia', 'bekasi', '2026-07-15 09:28:46'),
(8, 'Arrlo', 'Kucing', 'Kucing kitten British shorthair bluesolid Female p', '2026-05-16', '2026-07-15', 'BSH Female 2.5 month, berat 1000 (g)\r\n\r\nHARGA : Rp 2,850,000', '6a57e59cbe98a.jpg', 'Tersedia', 'Yogyakarta', '2026-07-15 19:53:06'),
(9, 'Ale', 'Kucing', 'Kitten Persia Medium mix British Long Hair', '2026-04-15', '2026-07-14', 'Jantan, usia 2.5 bulan, bulu super kapas, pee dan pup sudah mandiri, super aktif.\r\n\r\nHARGA : Rp 950,000', '6a57e590bbbbd.jpg', 'Tersedia', 'Yogyakarta', '2026-07-15 19:54:56'),
(10, 'Whiskey', 'Anjing', 'Husky', '2025-07-02', '2026-07-14', 'Vaksin 3x\r\nNama: Whiskey\r\nAlasan dilepas: Ada bayi ngga bisa ngurus lagi. Cuma tiap hari dikasih makan dan minum dikurung di kamar aja ga pernah diajak jalan. Kasihan', '6a57e5f9dc15b.jpg', 'Tersedia', 'Yogyakarta', '2026-07-15 19:56:41'),
(11, 'Hanawa', 'Anjing', 'Alaskan malamute puppy', '2025-08-21', '2026-07-14', 'Surat lengkap (pedigree)\r\nKondisi sehat dan aktif\r\nSudah mendapatkan perawatan sesuai usia\r\nPengiriman luar kota bisa didiskusikan\r\n\r\nHARGA : 8.000.000', '6a57e6aa28674.jpg', 'Diadopsi', 'Yogyakarta', '2026-07-15 19:59:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`id_hewan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `hewan`
--
ALTER TABLE `hewan`
  MODIFY `id_hewan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
