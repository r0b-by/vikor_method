-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jun 2025 pada 10.21
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
-- Database: `vikorrobby`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatifs`
--

CREATE TABLE `alternatifs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alternatif_code` varchar(255) NOT NULL,
  `alternatif_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `alternatifs`
--

INSERT INTO `alternatifs` (`id`, `alternatif_code`, `alternatif_name`) VALUES
(1, 'A1', 'Ahmad Fauzan'),
(2, 'A2', 'Budi Santoso'),
(3, 'A3', 'Citra Dewi'),
(4, 'A4', 'Dian Purnama'),
(5, 'A5', 'Eka Ramadhan'),
(6, 'A6', 'Farah Aulia'),
(7, 'A7', 'Gilang Saputra'),
(8, 'A8', 'Hana Salsabila'),
(9, 'A9', 'Indra Wijaya'),
(10, 'A10', 'Joko Prasetyo'),
(11, 'A11', 'Kurniawan Hidayat'),
(12, 'A12', 'Lestari Dewi'),
(13, 'A13', 'Mahmud Risky'),
(14, 'A14', 'Nadya Amelia'),
(15, 'A15', 'Omar Zaki'),
(16, 'A16', 'Putri Ayu'),
(17, 'A17', 'Qori Rahma'),
(18, 'A18', 'Rizky Fadilah'),
(19, 'A19', 'Siti Nurhaliza'),
(20, 'A20', 'Taufik Hidayat'),
(21, 'A21', 'Umar Alfaruq'),
(22, 'A22', 'Vina Maharani'),
(23, 'A23', 'Wahyu Pradana'),
(24, 'A24', 'Xavier Muhammad'),
(25, 'A25', 'Yusuf Kurnia'),
(26, 'A26', 'Zahra Melati'),
(27, 'A27', 'Agus Saputra'),
(28, 'A28', 'Bella Safira'),
(29, 'A29', 'Dedy Firmansyah'),
(30, 'A30', 'Erika Putri');

-- --------------------------------------------------------

--
-- Struktur dari tabel `criterias`
--

CREATE TABLE `criterias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `criteria_code` varchar(255) NOT NULL,
  `criteria_name` varchar(255) NOT NULL,
  `criteria_type` varchar(255) NOT NULL,
  `weight` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `criterias`
--

INSERT INTO `criterias` (`id`, `criteria_code`, `criteria_name`, `criteria_type`, `weight`) VALUES
(1, 'C1', 'Nilai Akademik', 'Benefit', 0.3),
(2, 'C2', 'Jumlah Pendapatan Ort', 'Cost', 0.25),
(3, 'C3', 'Jumlah Tanggungan Ort', 'Benefit', 0.2),
(4, 'C4', 'Prestasi Akademik', 'Benefit', 0.15),
(5, 'C5', 'Prestasi Non-Akademik', 'Benefit', 0.1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_vikor`
--

CREATE TABLE `hasil_vikor` (
  `id` int(11) NOT NULL,
  `id_alternatif` bigint(20) UNSIGNED DEFAULT NULL,
  `nilai_s` double DEFAULT NULL,
  `nilai_r` double DEFAULT NULL,
  `nilai_q` double DEFAULT NULL,
  `ranking` int(11) DEFAULT NULL,
  `status` enum('Lulus','Tidak Lulus') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_vikor`
--

INSERT INTO `hasil_vikor` (`id`, `id_alternatif`, `nilai_s`, `nilai_r`, `nilai_q`, `ranking`, `status`, `created_at`) VALUES
(1, 1, 0.286, 0.1, 0.145, 3, 'Lulus', '2025-06-13 05:36:33'),
(2, 2, 0.458, 0.168, 0.438, 12, 'Tidak Lulus', '2025-06-13 05:36:33'),
(3, 3, 0.324, 0.144, 0.27, 7, 'Lulus', '2025-06-13 05:36:33'),
(4, 4, 0.559, 0.187, 0.566, 16, 'Tidak Lulus', '2025-06-13 05:36:33'),
(5, 5, 0.717, 0.24, 0.816, 24, 'Tidak Lulus', '2025-06-13 05:36:33'),
(6, 6, 0.749, 0.252, 0.869, 26, 'Tidak Lulus', '2025-06-13 05:36:33'),
(7, 7, 0.394, 0.132, 0.306, 9, 'Lulus', '2025-06-13 05:36:33'),
(8, 8, 0.431, 0.125, 0.324, 10, 'Lulus', '2025-06-13 05:36:33'),
(9, 9, 0.623, 0.168, 0.583, 18, 'Tidak Lulus', '2025-06-13 05:36:33'),
(10, 10, 0.655, 0.228, 0.737, 21, 'Tidak Lulus', '2025-06-13 05:36:33'),
(11, 11, 0.265, 0.125, 0.179, 5, 'Lulus', '2025-06-13 05:36:33'),
(12, 12, 0.438, 0.156, 0.395, 11, 'Tidak Lulus', '2025-06-13 05:36:33'),
(13, 13, 0.288, 0.096, 0.138, 2, 'Lulus', '2025-06-13 05:36:33'),
(14, 14, 0.783, 0.288, 0.975, 30, 'Tidak Lulus', '2025-06-13 05:36:33'),
(15, 15, 0.685, 0.3, 0.914, 28, 'Tidak Lulus', '2025-06-13 05:36:33'),
(16, 16, 0.58, 0.288, 0.797, 23, 'Tidak Lulus', '2025-06-13 05:36:33'),
(17, 17, 0.298, 0.168, 0.298, 8, 'Lulus', '2025-06-13 05:36:33'),
(18, 18, 0.37, 0.24, 0.512, 14, 'Tidak Lulus', '2025-06-13 05:36:33'),
(19, 19, 0.765, 0.228, 0.833, 25, 'Tidak Lulus', '2025-06-13 05:36:33'),
(20, 20, 0.212, 0.062, 0, 1, 'Lulus', '2025-06-13 05:36:33'),
(21, 21, 0.41, 0.2, 0.463, 13, 'Tidak Lulus', '2025-06-13 05:36:33'),
(22, 22, 0.591, 0.204, 0.63, 20, 'Tidak Lulus', '2025-06-13 05:36:33'),
(23, 23, 0.29, 0.1, 0.148, 4, 'Lulus', '2025-06-13 05:36:33'),
(24, 24, 0.539, 0.187, 0.549, 15, 'Tidak Lulus', '2025-06-13 05:36:33'),
(25, 25, 0.559, 0.187, 0.566, 17, 'Tidak Lulus', '2025-06-13 05:36:33'),
(26, 26, 0.675, 0.3, 0.905, 27, 'Tidak Lulus', '2025-06-13 05:36:33'),
(27, 27, 0.325, 0.125, 0.231, 6, 'Lulus', '2025-06-13 05:36:33'),
(28, 28, 0.555, 0.2, 0.59, 19, 'Tidak Lulus', '2025-06-13 05:36:33'),
(29, 29, 0.74, 0.3, 0.962, 29, 'Tidak Lulus', '2025-06-13 05:36:33'),
(30, 30, 0.673, 0.228, 0.752, 22, 'Tidak Lulus', '2025-06-13 05:36:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_11_15_013425_create_criterias_table', 1),
(7, '2023_11_20_061811_create_alternatifs_table', 1),
(8, '2023_11_22_030108_create_penilaians_table', 1),
(9, '2025_04_20_182459_create_hasil_vikor_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaians`
--

CREATE TABLE `penilaians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_alternatif` bigint(20) UNSIGNED NOT NULL,
  `id_criteria` bigint(20) UNSIGNED NOT NULL,
  `nilai` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penilaians`
--

INSERT INTO `penilaians` (`id`, `id_alternatif`, `id_criteria`, `nilai`) VALUES
(1, 1, 1, 87),
(2, 1, 2, 20),
(3, 1, 3, 100),
(4, 1, 4, 40),
(5, 1, 5, 0),
(6, 2, 1, 81),
(7, 2, 2, 20),
(8, 2, 3, 40),
(9, 2, 4, 60),
(10, 2, 5, 20),
(11, 3, 1, 83),
(12, 3, 2, 20),
(13, 3, 3, 60),
(14, 3, 4, 100),
(15, 3, 5, 20),
(16, 4, 1, 84),
(17, 4, 2, 80),
(18, 4, 3, 60),
(19, 4, 4, 60),
(20, 4, 5, 20),
(21, 5, 1, 75),
(22, 5, 2, 80),
(23, 5, 3, 40),
(24, 5, 4, 60),
(25, 5, 5, 20),
(26, 6, 1, 74),
(27, 6, 2, 80),
(28, 6, 3, 40),
(29, 6, 4, 40),
(30, 6, 5, 30),
(31, 7, 1, 84),
(32, 7, 2, 40),
(33, 7, 3, 60),
(34, 7, 4, 100),
(35, 7, 5, 0),
(36, 8, 1, 92),
(37, 8, 2, 60),
(38, 8, 3, 60),
(39, 8, 4, 40),
(40, 8, 5, 20),
(41, 9, 1, 81),
(42, 9, 2, 60),
(43, 9, 3, 60),
(44, 9, 4, 0),
(45, 9, 5, 20),
(46, 10, 1, 76),
(47, 10, 2, 80),
(48, 10, 3, 60),
(49, 10, 4, 60),
(50, 10, 5, 20),
(51, 11, 1, 90),
(52, 11, 2, 60),
(53, 11, 3, 100),
(54, 11, 4, 100),
(55, 11, 5, 20),
(56, 12, 1, 82),
(57, 12, 2, 40),
(58, 12, 3, 80),
(59, 12, 4, 40),
(60, 12, 5, 20),
(61, 13, 1, 87),
(62, 13, 2, 40),
(63, 13, 3, 80),
(64, 13, 4, 100),
(65, 13, 5, 20),
(66, 14, 1, 71),
(67, 14, 2, 60),
(68, 14, 3, 20),
(69, 14, 4, 40),
(70, 14, 5, 20),
(71, 15, 1, 70),
(72, 15, 2, 60),
(73, 15, 3, 60),
(74, 15, 4, 60),
(75, 15, 5, 0),
(76, 16, 1, 71),
(77, 16, 2, 40),
(78, 16, 3, 100),
(79, 16, 4, 0),
(80, 16, 5, 20),
(81, 17, 1, 81),
(82, 17, 2, 20),
(83, 17, 3, 80),
(84, 17, 4, 100),
(85, 17, 5, 20),
(86, 18, 1, 75),
(87, 18, 2, 20),
(88, 18, 3, 80),
(89, 18, 4, 100),
(90, 18, 5, 20),
(91, 19, 1, 76),
(92, 19, 2, 80),
(93, 19, 3, 60),
(94, 19, 4, 0),
(95, 19, 5, 0),
(96, 20, 1, 95),
(97, 20, 2, 40),
(98, 20, 3, 80),
(99, 20, 4, 60),
(100, 20, 5, 60),
(101, 21, 1, 91),
(102, 21, 2, 40),
(103, 21, 3, 20),
(104, 21, 4, 100),
(105, 21, 5, 0),
(106, 22, 1, 78),
(107, 22, 2, 80),
(108, 22, 3, 80),
(109, 22, 4, 0),
(110, 22, 5, 100),
(111, 23, 1, 91),
(112, 23, 2, 40),
(113, 23, 3, 60),
(114, 23, 4, 100),
(115, 23, 5, 20),
(116, 24, 1, 89),
(117, 24, 2, 80),
(118, 24, 3, 40),
(119, 24, 4, 40),
(120, 24, 5, 60),
(121, 25, 1, 89),
(122, 25, 2, 80),
(123, 25, 3, 80),
(124, 25, 4, 0),
(125, 25, 5, 0),
(126, 26, 1, 70),
(127, 26, 2, 60),
(128, 26, 3, 100),
(129, 26, 4, 0),
(130, 26, 5, 0),
(131, 27, 1, 95),
(132, 27, 2, 60),
(133, 27, 3, 60),
(134, 27, 4, 60),
(135, 27, 5, 60),
(136, 28, 1, 90),
(137, 28, 2, 60),
(138, 28, 3, 20),
(139, 28, 4, 40),
(140, 28, 5, 20),
(141, 29, 1, 70),
(142, 29, 2, 100),
(143, 29, 3, 100),
(144, 29, 4, 0),
(145, 29, 5, 60),
(146, 30, 1, 76),
(147, 30, 2, 60),
(148, 30, 3, 40),
(149, 30, 4, 40),
(150, 30, 5, 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'robby', 'robby@example.com', '2025-04-16 04:49:29', '$2y$12$b8W.s7QY31/YKhXGhFD3D.3WHlC0rUOUO3uWMTUKK4OX/i42pS5dS', 'R7PK2HQlWzZ6TqWYQ54L4HcPNOXAmLQmAlH38NE4xOSkOjRDNljLwNIRTPay', '2025-04-16 04:49:30', '2025-04-16 04:49:30');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatifs`
--
ALTER TABLE `alternatifs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `criterias`
--
ALTER TABLE `criterias`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `hasil_vikor`
--
ALTER TABLE `hasil_vikor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_alternatif` (`id_alternatif`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `penilaians`
--
ALTER TABLE `penilaians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penilaians_id_alternatif_foreign` (`id_alternatif`),
  ADD KEY `penilaians_id_criteria_foreign` (`id_criteria`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatifs`
--
ALTER TABLE `alternatifs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `criterias`
--
ALTER TABLE `criterias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hasil_vikor`
--
ALTER TABLE `hasil_vikor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `penilaians`
--
ALTER TABLE `penilaians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil_vikor`
--
ALTER TABLE `hasil_vikor`
  ADD CONSTRAINT `fk_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatifs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penilaians`
--
ALTER TABLE `penilaians`
  ADD CONSTRAINT `penilaians_id_alternatif_foreign` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatifs` (`id`),
  ADD CONSTRAINT `penilaians_id_criteria_foreign` FOREIGN KEY (`id_criteria`) REFERENCES `criterias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
