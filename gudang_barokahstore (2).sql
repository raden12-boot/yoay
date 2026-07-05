-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 05 Jul 2026 pada 16.18
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
-- Database: `gudang_barokahstore`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `nama_game` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `is_extended` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `keperluan` enum('id','id_region','email','text') NOT NULL DEFAULT 'id',
  `has_variant` tinyint(1) DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `harga_terendah` decimal(10,2) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `games`
--

INSERT INTO `games` (`id`, `nama_game`, `kategori`, `gambar`, `is_extended`, `created_at`, `keperluan`, `has_variant`, `deskripsi`, `harga_terendah`, `is_new`) VALUES
(10, 'Freefire', 'Game', '1769001067_freefire.jpg', 0, '2026-01-21 13:11:07', 'id', 1, '', NULL, 0),
(11, 'Mobile Legends', 'Game', '1769075569_Beli ML Diamond.png', 0, '2026-01-22 09:52:49', 'id_region', 1, '', NULL, 0),
(12, 'Indosat', 'Pulsa', '1769075689_images (4).png', 0, '2026-01-22 09:54:49', 'text', 1, NULL, NULL, 0),
(13, 'Telkomsel', 'Pulsa', '1769081057_telkomsel_telkomsel_rafi_prepaid_full67_s7fle83.jpg', 0, '2026-01-22 11:24:17', 'text', 1, NULL, NULL, 0),
(14, 'Tri', 'Pulsa', '1769081620_tripulsa.jpg', 0, '2026-01-22 11:33:40', 'text', 1, NULL, NULL, 0),
(15, 'Smartfren', 'Pulsa', '1769082168_smartfen.jpg', 0, '2026-01-22 11:42:48', 'text', 1, NULL, NULL, 0),
(16, 'XL', 'Pulsa', '1769082701_xl.png', 0, '2026-01-22 11:51:41', 'text', 1, NULL, NULL, 0),
(17, 'By.U', 'Pulsa', '1769082815_byu.jpg', 0, '2026-01-22 11:53:35', 'text', 1, NULL, NULL, 0),
(18, 'Axis', 'Pulsa', '1769091091_axis.png', 0, '2026-01-22 14:11:31', 'text', 1, NULL, NULL, 0),
(19, 'Internet Telkomsel Jabo-Banten', 'Voucher', '1769091957_telkomsel_telkomsel_rafi_prepaid_full67_s7fle83.jpg', 0, '2026-01-22 14:25:57', 'text', 1, NULL, NULL, 0),
(20, 'Voucher Wifi ID', 'Voucher', '1769092552_wifi-id.png', 0, '2026-01-22 14:35:52', 'text', 1, NULL, NULL, 0),
(21, 'Matrix TV', 'Voucher', '1769155039_matrixtv.jpg', 0, '2026-01-23 07:57:19', 'text', 1, NULL, NULL, 0),
(22, 'NexParabola', 'Voucher', '1769155327_nexparabola.jpg', 0, '2026-01-23 08:02:07', 'text', 1, NULL, NULL, 0),
(23, 'TRANSVISION ALL TIPE DECODER', 'Voucher', '1769155652_transvision.jpg', 0, '2026-01-23 08:07:32', 'text', 1, NULL, NULL, 0),
(24, 'TRANSVISION X-STREAM', 'Voucher', '1769155797_transvision-xstream.jpeg', 0, '2026-01-23 08:09:57', 'text', 1, NULL, NULL, 0),
(29, 'Robux Login', 'Game', '1769249688_roblox.jpg', 0, '2026-01-24 10:14:48', 'id_region', 1, 'ID Game : Email Roblox Anda, Dan Untuk Pengisian \r\nRegion / Server : Password Roblox anda lalu sertakan security code minimal 3,\r\nContoh Pengisian Paasword + Security code :\r\n\"gudangbarokah123 , ABCD , ABCD , ABCD\"', NULL, 0),
(32, 'Netflix', 'App Premium', '1769483308_NetflixBanner.jpg', 0, '2026-01-27 03:01:57', 'email', 1, 'Harap Berikan Email Di Kolom UserID ,, dan Mengisi Nomor Whatsapp Aktif ,, Ganti PIN / ID Dikenakan DENDA Senilai Rp600.000 , Garansi 7 Hari Setelah Pembelian ,, Jika Ada Kendala Hubungin CS +6285888223922', NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `game_fields`
--

CREATE TABLE `game_fields` (
  `id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `field_name` varchar(50) DEFAULT NULL,
  `field_key` varchar(50) DEFAULT NULL,
  `required` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `resi` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data_game` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_game`)),
  `nama_pengirim` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `bukti_tf` varchar(255) DEFAULT NULL,
  `status` enum('PENDING','SELESAI','BATAL') DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `variant_id` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `telegram_msg_id` bigint(20) DEFAULT NULL,
  `telegram_chat_id` bigint(20) DEFAULT NULL,
  `voucher_code` varchar(512) DEFAULT NULL,
  `diskon_voucher` decimal(10,2) DEFAULT 0.00,
  `harga_sebelum_diskon` decimal(10,2) DEFAULT NULL,
  `account_email` varchar(100) DEFAULT NULL,
  `account_password` text DEFAULT NULL,
  `account_security` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `resi`, `user_id`, `data_game`, `nama_pengirim`, `payment_method`, `bukti_tf`, `status`, `created_at`, `variant_id`, `user_input`, `telegram_msg_id`, `telegram_chat_id`, `voucher_code`, `diskon_voucher`, `harga_sebelum_diskon`, `account_email`, `account_password`, `account_security`) VALUES
(1, '', NULL, NULL, 'Raden', NULL, '1766573615_GUDANG-BAROKAH-removebg-preeview.png', 'PENDING', '2025-12-24 10:53:35', 1, '123456', NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL),
(4, 'TRX2025122', NULL, NULL, 'Raden', 'GOPAY', '1766574217_G498123682-0703A01-default.png', 'PENDING', '2025-12-24 11:03:37', 3, '123456', NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL),
(12, 'TRX20251226182302D1E3', NULL, NULL, 'Fatsa', 'SHOPEEPAY', '1766769782_Raden.png', 'SELESAI', '2025-12-26 17:23:02', 5, '4321569900', NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL),
(13, 'TRX202512261837054D3C', NULL, NULL, 'Ariyanto', 'MANDIRI', '1766770625_SoccerChannel2020.jpg', 'PENDING', '2025-12-26 17:37:05', 5, '098912011', 206, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(14, 'TRX20251226185243CA4E', NULL, NULL, 'RadenMufatsa', 'GOPAY', '1766771563_Raden.png', 'SELESAI', '2025-12-26 17:52:43', 5, '0987654321', 208, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(15, 'TRX2025122710360135CF', NULL, NULL, 'RadenMufatsa', 'GOPAY', '1766828161_Raden.png', 'SELESAI', '2025-12-27 09:36:01', 5, '0987654321', 209, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(16, 'TRX202512271135069477', NULL, NULL, 'biru', 'OVO', '1766831706_bein-1-4bd997.webp', 'BATAL', '2025-12-27 10:35:06', 6, 'ID: 123456 | Region: 098765432', 210, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(17, 'TRX20251227115218B3EB', NULL, NULL, 'adin', 'OVO', '1766832738_WhatsApp Image 2025-12-18 at 7.53.29 PM.jpeg', 'PENDING', '2025-12-27 10:52:18', 8, 'ID: 123456 | Region: test', 214, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(18, 'TRX20251227123238DEAC', NULL, NULL, 'Raden123', 'GOPAY', '1766835158_images (1).jpg', 'SELESAI', '2025-12-27 11:32:38', 8, 'ID: 123456 | Region: test', 215, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(19, 'TRX202512271240431A38', NULL, NULL, 'Raden123', 'GOPAY', '1766835643_images (1).jpg', 'PENDING', '2025-12-27 11:40:43', 8, 'ID: 123456 | Region: test', 216, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(20, 'TRX202601180700297A80', NULL, NULL, 'Raden', 'GOPAY', '1768716029_Screenshot 2026-01-18 124327.png', 'PENDING', '2026-01-18 06:00:29', 6, 'ID: 12345 | Region: test', 222, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(21, 'TRX20260120122726E272', NULL, NULL, 'testting', 'SHOPEEPAY', '1768908446_WhatsApp Image 2026-01-20 at 6.24.16 PM.jpeg', 'SELESAI', '2026-01-20 11:27:26', 8, 'ID: 123456 | Region: test', 227, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(22, 'TRX2026012012274675C9', NULL, NULL, 'testting', 'SHOPEEPAY', '1768908466_WhatsApp Image 2026-01-20 at 6.24.16 PM.jpeg', 'PENDING', '2026-01-20 11:27:46', 8, 'ID: 123456 | Region: test', 228, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(23, 'TRX20260120122802BA8B', NULL, NULL, 'testting', 'SHOPEEPAY', '1768908482_WhatsApp Image 2026-01-20 at 6.24.16 PM.jpeg', 'PENDING', '2026-01-20 11:28:02', 8, 'ID: 123456 | Region: test', 229, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(24, 'TRX20260120160840959C', NULL, NULL, 'Fatsa', 'MANDIRI', '1768921720_cekresi.jpg', 'SELESAI', '2026-01-20 15:08:40', 6, 'ID: 123456 | Region: test', 230, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(25, 'TRX20260121100127D1C8', NULL, NULL, 'Raden123', 'QRIS', '1768986087_cekresi.jpg', 'SELESAI', '2026-01-21 09:01:27', 5, '43215699', 231, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(26, 'TRX20260121100315EC3A', NULL, NULL, 'Raden123', 'QRIS', '1768986195_cekresi.jpg', 'SELESAI', '2026-01-21 09:03:15', 5, '43215699', 232, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(27, 'TRX2026012110032769AB', NULL, NULL, 'Raden123', 'QRIS', '1768986207_cekresi.jpg', 'SELESAI', '2026-01-21 09:03:27', 5, '43215699', 233, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(28, 'TRX20260123093613525A', NULL, NULL, 'Raden123', 'QRIS', '1769157373_frame (1).png', 'SELESAI', '2026-01-23 08:36:13', 287, '123456', 234, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(30, 'TRX2026012310013252AC', NULL, NULL, 'eee', 'QRIS', '1769158892_smartfen.jpg', 'PENDING', '2026-01-23 09:01:32', 289, 'ID: 123456 | Region: eee', 236, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(31, 'TRX20260124182652F9D3', NULL, NULL, 'dss', 'QRIS', '1769275612_Netflix_Logo_PMS.png', 'SELESAI', '2026-01-24 17:26:52', 60, 'ID: 123456 | Region: test', NULL, NULL, NULL, 0.00, NULL, '', '', ''),
(32, 'TRX20260124183005CF75', NULL, NULL, 'Raden123', 'QRIS', '1769275805_Netflix_Logo_PMS.png', 'PENDING', '2026-01-24 17:30:05', 291, '43215699', 237, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(33, 'TRX202601250754388662', NULL, NULL, 'Raden', 'QRIS', '1769324078_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:38', 290, 'ID: 123456 | Region: eee', 238, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(34, 'TRX202601250754407A0A', NULL, NULL, 'Raden', 'QRIS', '1769324080_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:40', 290, 'ID: 123456 | Region: eee', 239, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(35, 'TRX20260125075441571B', NULL, NULL, 'Raden', 'QRIS', '1769324081_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:41', 290, 'ID: 123456 | Region: eee', 241, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(36, 'TRX2026012507544188DA', NULL, NULL, 'Raden', 'QRIS', '1769324081_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:41', 290, 'ID: 123456 | Region: eee', 240, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(37, 'TRX202601250754413F25', NULL, NULL, 'Raden', 'QRIS', '1769324081_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:41', 290, 'ID: 123456 | Region: eee', 242, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(38, 'TRX2026012507544276C6', NULL, NULL, 'Raden', 'QRIS', '1769324082_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:42', 290, 'ID: 123456 | Region: eee', 243, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(39, 'TRX202601250754425F85', NULL, NULL, 'Raden', 'QRIS', '1769324082_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:42', 290, 'ID: 123456 | Region: eee', 244, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(40, 'TRX202601250754429694', NULL, NULL, 'Raden', 'QRIS', '1769324082_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:42', 290, 'ID: 123456 | Region: eee', 245, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(41, 'TRX2026012507544330A7', NULL, NULL, 'Raden', 'QRIS', '1769324083_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:43', 290, 'ID: 123456 | Region: eee', 246, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(42, 'TRX20260125075443143B', NULL, NULL, 'Raden', 'QRIS', '1769324083_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:43', 290, 'ID: 123456 | Region: eee', 247, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(43, 'TRX202601250754433049', NULL, NULL, 'Raden', 'QRIS', '1769324083_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:43', 290, 'ID: 123456 | Region: eee', 249, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(44, 'TRX202601250754430AAB', NULL, NULL, 'Raden', 'QRIS', '1769324083_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:43', 290, 'ID: 123456 | Region: eee', 248, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(45, 'TRX2026012507544426C8', NULL, NULL, 'Raden', 'QRIS', '1769324084_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:44', 290, 'ID: 123456 | Region: eee', 250, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(46, 'TRX202601250754440B93', NULL, NULL, 'Raden', 'QRIS', '1769324084_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:44', 290, 'ID: 123456 | Region: eee', 252, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(47, 'TRX202601250754443372', NULL, NULL, 'Raden', 'QRIS', '1769324084_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:44', 290, 'ID: 123456 | Region: eee', 251, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(48, 'TRX202601250754441E48', NULL, NULL, 'Raden', 'QRIS', '1769324084_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:44', 290, 'ID: 123456 | Region: eee', 254, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(49, 'TRX20260125075444CE28', NULL, NULL, 'Raden', 'QRIS', '1769324084_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:44', 290, 'ID: 123456 | Region: eee', 253, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(50, 'TRX20260125075445641F', NULL, NULL, 'Raden', 'QRIS', '1769324085_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:45', 290, 'ID: 123456 | Region: eee', 255, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(51, 'TRX20260125075445116A', NULL, NULL, 'Raden', 'QRIS', '1769324085_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:45', 290, 'ID: 123456 | Region: eee', 256, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(52, 'TRX2026012507544546AF', NULL, NULL, 'Raden', 'QRIS', '1769324085_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:45', 290, 'ID: 123456 | Region: eee', 257, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(53, 'TRX202601250754467630', NULL, NULL, 'Raden', 'QRIS', '1769324086_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:46', 290, 'ID: 123456 | Region: eee', 258, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(54, 'TRX202601250754465EA5', NULL, NULL, 'Raden', 'QRIS', '1769324086_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:46', 290, 'ID: 123456 | Region: eee', 260, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(55, 'TRX20260125075446F0FB', NULL, NULL, 'Raden', 'QRIS', '1769324086_nexparabola.jpg', 'PENDING', '2026-01-25 06:54:46', 290, 'ID: 123456 | Region: eee', 259, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(56, 'TRX202601250754462FCC', NULL, NULL, 'Raden', 'QRIS', '1769324086_nexparabola.jpg', 'SELESAI', '2026-01-25 06:54:46', 290, 'ID: 123456 | Region: eee', 261, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(57, 'TRX20260125075555D986', NULL, NULL, 'ww', 'QRIS', '1769324155_Netflix_Logo_PMS.png', 'BATAL', '2026-01-25 06:55:55', 290, 'ID: ww | Region: test', 262, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(58, 'TRX20260125082805C25B', NULL, NULL, '085888223922', 'QRIS', '1769326085_SnapInsta.to_616008344_18358507702204388_4686692651647062916_n.jpg', 'BATAL', '2026-01-25 07:28:05', 296, 'ID: 123456 | Region: test', 263, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(59, 'TRX2026012508282623BE', NULL, NULL, '085888223922', 'QRIS', '1769326106_SnapInsta.to_616008344_18358507702204388_4686692651647062916_n.jpg', 'PENDING', '2026-01-25 07:28:26', 296, 'ID: 123456 | Region: test', 264, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(60, 'TRX2026021311300374D0', NULL, NULL, '2345', 'QRIS', '1770978603_pngtree-sports-balls-3d-illustration-png-image_9235520.png', 'SELESAI', '2026-02-13 10:30:03', 57, '12345', 266, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(61, 'TRX20260213113029D100', NULL, NULL, '2345', 'QRIS', '1770978629_pngtree-sports-balls-3d-illustration-png-image_9235520.png', 'PENDING', '2026-02-13 10:30:29', 57, '12345', 267, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(62, 'TRX202602131142105C11', NULL, NULL, 'Raden123', 'QRIS', '1770979330_WhatsApp Image 2026-02-09 at 18.10.00.jpeg', 'PENDING', '2026-02-13 10:42:10', 345, 'ID: 12345 | Region: eee', 268, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(63, 'TRX2026021311421128BE', NULL, NULL, 'Raden123', 'QRIS', '1770979331_WhatsApp Image 2026-02-09 at 18.10.00.jpeg', 'PENDING', '2026-02-13 10:42:11', 345, 'ID: 12345 | Region: eee', 269, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(64, 'TRX2026021311425178AD', NULL, NULL, 'testting', 'QRIS', '1770979371_pm14-5e0726bb-cb8f-4292-8d4e-41b98d3bcbe7_1_11zon.jpg', 'SELESAI', '2026-02-13 10:42:51', 257, '43215699', 270, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(65, 'TRX202602131151005370', NULL, NULL, 'Raden', 'QRIS', '1770979860_WhatsApp Image 2026-02-09 at 6.49.33 PM.jpeg', 'PENDING', '2026-02-13 10:51:00', 221, '0987654321', 271, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(66, 'TRX20260214140535C26B', NULL, NULL, 'Raden', 'QRIS', '1771074335_WhatsApp Image 2026-02-09 at 6.49.33 PM.jpeg', 'PENDING', '2026-02-14 13:05:35', 221, '0987654321', 273, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(67, 'TRX2026021414055268D0', NULL, NULL, 'https://t.me/DonzTelevisionandFriend', 'QRIS', '1771074352_pngtree-sports-balls-3d-illustration-png-image_9235520.png', 'PENDING', '2026-02-14 13:05:52', 17, 'https://t.me/DonzTelevisionandFriend', 274, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(68, 'TRX202602161318158AF7', NULL, NULL, 'https://t.me/DonzTelevisionandFriend', 'QRIS', '1771244295_pngtree-sports-balls-3d-illustration-png-image_9235520.png', 'PENDING', '2026-02-16 12:18:15', 17, 'https://t.me/DonzTelevisionandFriend', 275, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(69, 'TEST1771572142', NULL, NULL, 'TEST USER', 'QRIS', 'test.jpg', 'PENDING', '2026-02-20 07:22:22', 362, 'TEST', NULL, NULL, 'EPEPDIDADA', 33000.00, 33000.00, NULL, NULL, NULL),
(70, 'TRX202602200824371A1D', NULL, NULL, 'Raden123', 'QRIS', '1771572277_Screenshot 2026-02-14 155347.png', 'PENDING', '2026-02-20 07:24:37', 12, '43215699', 276, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(71, 'TRX2026022008281919B8', NULL, NULL, '23456', 'QRIS', '1771572499_WhatsApp Image 2026-02-09 at 6.49.33 PM.jpeg', 'SELESAI', '2026-02-20 07:28:19', 11, '23456', 277, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(72, 'TRX202602200838403EB2', NULL, NULL, '23456', 'QRIS', '1771573120_WhatsApp Image 2026-02-09 at 6.49.33 PM.jpeg', 'SELESAI', '2026-02-20 07:38:40', 11, '23456', 278, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(73, 'TEST1771573178', NULL, NULL, 'TEST USER', 'QRIS', 'test.jpg', 'PENDING', '2026-02-20 07:39:38', 362, 'TEST', NULL, NULL, 'EPEPDIDADA', 3300.00, 33000.00, NULL, NULL, NULL),
(74, 'TRX202602200845115302', NULL, NULL, 'Raden', 'QRIS', '1771573511_byu.webp', 'SELESAI', '2026-02-20 07:45:11', 11, '123456', 279, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(75, 'TRX20260220085117F324', NULL, NULL, 'testting', 'QRIS', '1771573877_pngtree-sports-balls-3d-illustration-png-image_9235520.png', 'PENDING', '2026-02-20 07:51:17', 11, '123456', NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL),
(76, 'TRX202602200851438BE1', NULL, NULL, 'testting', 'QRIS', '1771573903_pngtree-sports-balls-3d-illustration-png-image_9235520.png', 'SELESAI', '2026-02-20 07:51:43', 11, '123456', 280, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(77, 'TRX20260220085947D9DA', NULL, NULL, 'Raden', 'QRIS', '1771574387_WhatsApp Image 2026-02-09 at 6.49.33 PM.jpeg', 'SELESAI', '2026-02-20 07:59:47', 11, '0987654321', 281, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(78, 'TRX20260220091902AEE9', NULL, NULL, 'Fatsa', 'QRIS', '1771575542_Screenshot 2026-02-14 155347.png', 'SELESAI', '2026-02-20 08:19:02', 21, '43215699', NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL),
(79, 'TRX20260220092421EF3F', NULL, NULL, 'Fatsa', 'QRIS', '1771575861_Screenshot 2026-02-14 155347.png', 'SELESAI', '2026-02-20 08:24:21', 21, '43215699', 282, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(80, 'TRX20260222084005C598', NULL, NULL, 'Raden', 'QRIS', '1771746005_download (7).png', 'PENDING', '2026-02-22 07:40:05', 345, 'ID: w | Region: w', 283, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(81, 'TRX20260222084005EF3C', NULL, NULL, 'Raden', 'QRIS', '1771746005_download (7).png', 'PENDING', '2026-02-22 07:40:05', 345, 'ID: w | Region: w', 284, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(82, 'TRX202606260207034073', NULL, NULL, 'Raden', 'QRIS', '1782432423_WhatsApp Image 2026-06-14 at 16.23.02 (1).jpeg', 'SELESAI', '2026-06-26 00:07:03', 21, '123456789', 292, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL),
(83, 'TRX20260704145959F067', NULL, NULL, 'kepo', 'QRIS', '1783169999_G92M_JPWAAAVVRs.jpg', 'SELESAI', '2026-07-04 12:59:59', 350, 'ID: 80085 | Region: ASIA', 293, 7221419012, NULL, 0.00, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga_modal` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `variant_group` varchar(50) DEFAULT NULL,
  `nama_variant` varchar(100) DEFAULT NULL,
  `harga_awal` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_variants`
--

INSERT INTO `product_variants` (`id`, `game_id`, `variant_group`, `nama_variant`, `harga_awal`, `harga_jual`, `stok`, `created_at`, `product_id`) VALUES
(11, 10, 'Diamonds', '15 Diamonds', 2383, 3500, 999, '2026-01-21 13:11:07', NULL),
(12, 10, 'Diamonds', '20 Diamonds', 3152, 4000, 999, '2026-01-21 13:11:07', NULL),
(13, 10, 'Diamonds', '30 Diamonds', 4690, 5500, 999, '2026-01-21 13:11:07', NULL),
(14, 10, 'Diamonds', '50 Diamonds', 6238, 7500, 999, '2026-01-21 13:11:07', NULL),
(15, 10, 'Diamonds', '40 Diamonds', 6238, 7500, 999, '2026-01-21 13:11:07', NULL),
(16, 10, 'Diamonds', '60 Diamonds', 7786, 8500, 999, '2026-01-21 13:11:07', NULL),
(17, 10, 'Diamonds', '70 Diamonds', 8555, 9500, 999, '2026-01-21 13:11:07', NULL),
(18, 10, 'Diamonds', '75 Diamonds', 9324, 10500, 999, '2026-01-21 13:11:07', NULL),
(19, 10, 'Diamonds', '80 Diamonds', 10093, 10500, 999, '2026-01-21 13:11:07', NULL),
(20, 10, 'Diamonds', '90 Diamonds', 11646, 12500, 999, '2026-01-21 13:11:07', NULL),
(21, 10, 'Diamonds', '100 Diamonds', 12415, 13500, 999, '2026-01-21 13:11:07', NULL),
(22, 10, 'Diamonds', '95 Diamonds', 12415, 13500, 999, '2026-01-21 13:11:07', NULL),
(23, 10, 'Diamonds', '120 Diamonds', 14722, 16500, 999, '2026-01-21 13:11:07', NULL),
(24, 10, 'Diamonds', '125 Diamonds', 16225, 17500, 999, '2026-01-21 13:11:07', NULL),
(25, 10, 'Diamonds', '130 Diamonds', 16280, 17500, 999, '2026-01-21 13:11:07', NULL),
(26, 10, 'Diamonds', '140 Diamonds', 17049, 18500, 999, '2026-01-21 13:11:07', NULL),
(27, 10, 'Diamonds', '150 Diamonds', 18587, 19500, 999, '2026-01-21 13:11:07', NULL),
(28, 10, 'Diamonds', '160 Diamonds', 20125, 21500, 999, '2026-01-21 13:11:07', NULL),
(29, 10, 'Diamonds', '170 Diamonds', 21098, 21500, 999, '2026-01-21 13:11:07', NULL),
(30, 10, 'Diamonds', '180 Diamonds', 22512, 23500, 999, '2026-01-21 13:11:07', NULL),
(31, 10, 'Diamonds', '190 Diamonds', 23419, 24500, 999, '2026-01-21 13:11:07', NULL),
(32, 10, 'Diamonds', '200 Diamonds', 24819, 27500, 999, '2026-01-21 13:11:07', NULL),
(33, 10, 'Diamonds', '210 Diamonds', 25588, 28500, 999, '2026-01-21 13:11:07', NULL),
(34, 10, 'Diamonds', '230 Diamonds', 29453, 30500, 999, '2026-01-21 13:11:07', NULL),
(35, 10, 'Diamonds', '250 Diamonds', 31087, 32500, 999, '2026-01-21 13:11:07', NULL),
(36, 10, 'Diamonds', '260 Diamonds', 32044, 34000, 999, '2026-01-21 13:11:07', NULL),
(37, 10, 'Diamonds', '280 Diamonds', 34163, 35500, 999, '2026-01-21 13:11:07', NULL),
(38, 10, 'Diamonds', '300 Diamonds', 37290, 38500, 999, '2026-01-21 13:11:07', NULL),
(39, 10, 'Diamonds', '355 Diamonds', 37290, 38500, 999, '2026-01-21 13:11:07', NULL),
(40, 10, 'Diamonds', '350 Diamonds', 42823, 45000, 999, '2026-01-21 13:11:07', NULL),
(41, 10, 'Diamonds', '360Diamonds', 43834, 46000, 999, '2026-01-21 13:11:07', NULL),
(42, 10, 'Diamonds ', '375 Diamonds', 46255, 47500, 999, '2026-01-21 13:11:07', NULL),
(43, 10, 'Diamonds', '400 Diamonds', 49350, 50500, 999, '2026-01-21 13:11:07', NULL),
(44, 10, 'Level Up Pass', 'Level Up Pass Level 6', 4738, 5500, 999, '2026-01-21 13:11:07', NULL),
(45, 10, 'Level Up Pass', 'Level Up Pass Level 10', 7852, 9000, 999, '2026-01-21 13:11:07', NULL),
(46, 10, 'Level Up Pass', 'Level Up Pass Level 20', 7852, 9500, 999, '2026-01-21 13:11:08', NULL),
(47, 10, 'Level Up Pass', 'Level Up Pass Level 15', 7852, 10000, 999, '2026-01-21 13:11:08', NULL),
(48, 10, 'Level Up Pass', 'Level Up Pass Level 25', 7852, 10500, 999, '2026-01-21 13:11:08', NULL),
(49, 10, 'Level Up Pass', 'Level Up Pass Level 30', 12503, 13500, 999, '2026-01-21 13:11:08', NULL),
(50, 10, 'Membership', 'Membership Mingguan', 24973, 26000, 999, '2026-01-21 13:11:08', NULL),
(51, 10, 'Membership', 'Membership Mingguan + 20 DM', 29107, 30500, 999, '2026-01-21 13:11:08', NULL),
(52, 10, 'Membership', 'Membership Mingguan + 50 DM', 32302, 33500, 999, '2026-01-21 13:11:08', NULL),
(53, 10, 'BP Card', 'BP CARD', 39042, 40500, 999, '2026-01-21 13:11:08', NULL),
(54, 10, 'Membership', 'Membership Bulanan', 77629, 79000, 999, '2026-01-21 13:11:08', NULL),
(55, 10, 'Membership', 'Membership Bulanan + 20 DM', 81166, 82500, 999, '2026-01-21 13:11:08', NULL),
(56, 10, 'Membership', 'Membership Bulanan + 50 DM', 84260, 85500, 999, '2026-01-21 13:11:08', NULL),
(57, 10, 'Membership', 'Membership Bulanan + 70 DM', 84260, 87000, 999, '2026-01-21 13:11:08', NULL),
(60, 11, 'Diamonds', '10 Diamonds', 2909, 3500, 999, '2026-01-22 09:52:49', NULL),
(61, 11, 'Diamonds', '12 Diamonds', 3388, 4500, 999, '2026-01-22 09:52:49', NULL),
(62, 11, 'Diamonds', '14 Diamonds', 3883, 5000, 999, '2026-01-22 09:52:49', NULL),
(63, 11, 'Diamonds', '14 Diamonds', 4305, 5500, 999, '2026-01-22 09:52:49', NULL),
(64, 11, 'Diamonds', '17 Diamonds', 4582, 6000, 999, '2026-01-22 09:52:49', NULL),
(65, 11, 'Diamonds', '18 Diamonds', 4838, 6000, 999, '2026-01-22 09:52:49', NULL),
(66, 11, 'Diamonds', '19 Diamonds', 5289, 6500, 999, '2026-01-22 09:52:49', NULL),
(67, 11, 'Diamonds', '20 Diamonds', 5768, 7000, 999, '2026-01-22 09:52:49', NULL),
(68, 11, 'Diamonds', '22 Diamonds', 6240, 7500, 999, '2026-01-22 09:52:49', NULL),
(69, 11, 'Diamonds', '28 Diamonds', 7666, 8500, 999, '2026-01-22 09:52:49', NULL),
(70, 11, 'Diamonds', '30 Diamonds', 8176, 9500, 999, '2026-01-22 09:52:49', NULL),
(71, 11, 'Diamonds', '33 Diamonds', 9092, 10000, 999, '2026-01-22 09:52:49', NULL),
(72, 11, 'Diamonds', '36 Diamonds', 9554, 10500, 999, '2026-01-22 09:52:49', NULL),
(73, 11, 'Diamonds', '34 Diamonds', 9599, 11500, 999, '2026-01-22 09:52:49', NULL),
(74, 11, 'Diamonds', '44 Diamonds', 11464, 12500, 999, '2026-01-22 09:52:49', NULL),
(75, 11, 'Diamonds', '42 Diamonds', 11464, 12500, 999, '2026-01-22 09:52:49', NULL),
(76, 11, 'Diamonds', '46  Diamonds', 12467, 13500, 999, '2026-01-22 09:52:49', NULL),
(77, 11, 'Diamonds', '45 Diamonds', 12422, 13500, 999, '2026-01-22 09:52:49', NULL),
(78, 11, 'Diamonds', '50 Diamonds', 13373, 14500, 999, '2026-01-22 09:52:49', NULL),
(79, 11, 'Diamonds', '54 Diamonds', 14301, 15500, 999, '2026-01-22 09:52:49', NULL),
(80, 11, 'Diamonds', '59 Diamonds', 15272, 16500, 999, '2026-01-22 09:52:49', NULL),
(81, 11, 'Diamonds', '60 Diamonds', 16220, 17500, 999, '2026-01-22 09:52:49', NULL),
(82, 11, 'Diamonds', '64 Diamonds', 16693, 18000, 999, '2026-01-22 09:52:49', NULL),
(83, 11, 'Diamonds', '66 Diamonds', 17651, 18500, 999, '2026-01-22 09:52:49', NULL),
(84, 11, 'Diamonds', '67 Diamonds', 17682, 19000, 999, '2026-01-22 09:52:49', NULL),
(85, 11, 'Diamonds', '65 Diamonds', 17269, 18500, 999, '2026-01-22 09:52:49', NULL),
(86, 11, 'Diamonds', '70 Diamonds', 18587, 19500, 999, '2026-01-22 09:52:49', NULL),
(87, 11, 'Diamonds', '71 Diamonds', 18597, 19500, 999, '2026-01-22 09:52:49', NULL),
(88, 11, 'Diamonds', '74 Diamonds', 18961, 20000, 999, '2026-01-22 09:52:49', NULL),
(89, 11, 'Diamonds', '78 Diamonds', 20501, 21500, 999, '2026-01-22 09:52:49', NULL),
(90, 11, 'Diamonds', '80 Diamonds', 21042, 22500, 999, '2026-01-22 09:52:49', NULL),
(91, 11, 'Diamonds', '84 Diamonds', 21924, 23000, 999, '2026-01-22 09:52:49', NULL),
(92, 11, 'Diamonds', '85 Diamonds', 21980, 23000, 999, '2026-01-22 09:52:49', NULL),
(93, 11, 'Diamonds', '86 Diamonds', 22338, 23500, 999, '2026-01-22 09:52:49', NULL),
(94, 11, 'Diamonds', '83 Diamonds', 21924, 23000, 999, '2026-01-22 09:52:49', NULL),
(95, 11, 'Diamonds', '88 Diamonds', 22896, 24000, 999, '2026-01-22 09:52:49', NULL),
(96, 11, 'Diamonds', '92 Diamonds', 23776, 25000, 999, '2026-01-22 09:52:49', NULL),
(97, 11, 'Diamonds', '90 Diamonds', 23403, 24500, 999, '2026-01-22 09:52:49', NULL),
(98, 11, 'Diamonds', '89 Diamonds', 23345, 24500, 999, '2026-01-22 09:52:49', NULL),
(99, 11, 'Diamonds', '98 Diamonds', 25755, 28000, 999, '2026-01-22 09:52:49', NULL),
(100, 11, 'Diamonds', '100 Diamonds', 26144, 28500, 999, '2026-01-22 09:52:49', NULL),
(101, 11, 'Diamonds', '113 Diamonds', 29571, 30500, 999, '2026-01-22 09:52:49', NULL),
(102, 11, 'Diamonds', '112 Diamonds', 29548, 30500, 999, '2026-01-22 09:52:49', NULL),
(103, 11, 'Diamonds', '116 Diamonds', 30578, 21500, 999, '2026-01-22 09:52:49', NULL),
(104, 11, 'Diamonds', '128 Diamonds', 33433, 34500, 999, '2026-01-22 09:52:49', NULL),
(105, 11, 'Diamonds', '129 Diamonds', 33469, 35000, 999, '2026-01-22 09:52:49', NULL),
(106, 11, 'Diamonds', '140 Diamonds', 36692, 38000, 999, '2026-01-22 09:52:49', NULL),
(107, 11, 'Diamonds', '141 Diamonds', 36797, 38500, 999, '2026-01-22 09:52:49', NULL),
(108, 11, 'Diamonds', '144 Diamonds', 37283, 38500, 999, '2026-01-22 09:52:49', NULL),
(109, 11, 'Diamonds', '145 Diamonds', 37715, 40000, 999, '2026-01-22 09:52:49', NULL),
(110, 11, 'Diamonds', '148 Diamonds', 38009, 40000, 999, '2026-01-22 09:52:49', NULL),
(111, 11, 'Diamonds', '149 Diamonds', 38415, 40500, 999, '2026-01-22 09:52:49', NULL),
(112, 11, 'Diamonds', '150 Diamonds', 39121, 40500, 999, '2026-01-22 09:52:49', NULL),
(113, 11, 'Diamonds', '153 Diamonds', 39441, 41000, 999, '2026-01-22 09:52:49', NULL),
(114, 11, 'Diamonds', '165 Diamonds', 43031, 44500, 999, '2026-01-22 09:52:49', NULL),
(115, 11, 'Diamonds', '167 Diamonds', 43502, 44500, 999, '2026-01-22 09:52:49', NULL),
(116, 11, 'Diamonds', '168 Diamonds', 43953, 45000, 999, '2026-01-22 09:52:49', NULL),
(117, 11, 'Diamonds', '170 Diamonds', 44086, 45500, 999, '2026-01-22 09:52:49', NULL),
(118, 11, 'Diamonds', '172 Diamonds', 44817, 46000, 999, '2026-01-22 09:52:49', NULL),
(119, 11, 'Diamonds', '173 Diamonds', 44996, 46500, 999, '2026-01-22 09:52:49', NULL),
(120, 11, 'Diamonds', '176 Diamonds', 45976, 47000, 999, '2026-01-22 09:52:49', NULL),
(121, 11, 'Diamonds', '182 Diamonds', 47467, 48000, 999, '2026-01-22 09:52:49', NULL),
(122, 11, 'Diamonds', '184 Diamonds', 47896, 49500, 999, '2026-01-22 09:52:49', NULL),
(123, 11, 'Diamonds', '185 Diamonds', 48368, 49500, 999, '2026-01-22 09:52:49', NULL),
(124, 11, 'Diamonds', '194 Diamonds', 50740, 52000, 999, '2026-01-22 09:52:49', NULL),
(125, 11, 'Diamonds', '200 Diamonds', 52260, 53500, 999, '2026-01-22 09:52:49', NULL),
(126, 11, 'Diamonds', '210 Diamonds', 54597, 55500, 999, '2026-01-22 09:52:49', NULL),
(127, 11, 'Diamonds', '220 Diamonds', 57334, 58500, 999, '2026-01-22 09:52:49', NULL),
(128, 11, 'Diamonds', '222 Diamonds', 57334, 58500, 999, '2026-01-22 09:52:49', NULL),
(129, 11, 'Diamonds', '228 Diamonds', 59348, 60500, 999, '2026-01-22 09:52:49', NULL),
(130, 11, 'Diamonds', '240 Diamonds', 62099, 63500, 999, '2026-01-22 09:52:49', NULL),
(131, 11, 'Diamonds', '241 Diamonds', 62570, 64000, 999, '2026-01-22 09:52:49', NULL),
(132, 11, 'Diamonds', '250 Diamonds', 64943, 66000, 999, '2026-01-22 09:52:49', NULL),
(133, 11, 'Diamonds', '252 Diamonds', 65414, 66500, 999, '2026-01-22 09:52:49', NULL),
(134, 11, 'Diamonds', '257 Diamonds', 66920, 68000, 999, '2026-01-22 09:52:49', NULL),
(135, 11, 'Diamonds', '258 Diamonds', 66965, 68500, 999, '2026-01-22 09:52:49', NULL),
(136, 11, 'Diamonds', '259 Diamonds', 67436, 69000, 999, '2026-01-22 09:52:49', NULL),
(137, 12, 'Pulsa', '5rb', 6715, 7000, 999, '2026-01-22 09:55:02', NULL),
(138, 12, 'Pulsa', '10rb', 11685, 12000, 999, '2026-01-22 10:05:05', NULL),
(139, 12, 'Pulsa', '12rb', 12892, 14000, 999, '2026-01-22 10:05:05', NULL),
(140, 12, 'Pulsa', '15rb', 15690, 17000, 999, '2026-01-22 10:05:05', NULL),
(141, 12, 'Pulsa', '20rb', 20530, 22000, 999, '2026-01-22 10:05:05', NULL),
(142, 12, 'Pulsa', '25rb', 25460, 27000, 999, '2026-01-22 10:05:05', NULL),
(143, 12, 'Pulsa', '30rb', 30225, 32000, 999, '2026-01-22 10:05:05', NULL),
(144, 12, 'Pulsa', '40rb', 39685, 42000, 999, '2026-01-22 10:05:05', NULL),
(145, 12, 'Pulsa', '50rb', 49559, 52000, 999, '2026-01-22 10:05:05', NULL),
(146, 12, 'Pulsa', '60rb', 59480, 62000, 999, '2026-01-22 10:05:05', NULL),
(147, 12, 'Pulsa', '70rb', 69150, 72000, 999, '2026-01-22 10:05:05', NULL),
(148, 12, 'Pulsa', '80rb', 79175, 82000, 999, '2026-01-22 10:05:05', NULL),
(149, 12, 'Pulsa', '90rb', 88545, 92000, 999, '2026-01-22 10:05:05', NULL),
(150, 12, 'Pulsa', '100rb', 96230, 102000, 999, '2026-01-22 10:05:05', NULL),
(151, 12, 'Pulsa', '125rb', 121575, 127000, 999, '2026-01-22 10:05:05', NULL),
(152, 12, 'Pulsa', '150rb', 149775, 152000, 999, '2026-01-22 10:05:05', NULL),
(153, 13, 'Pulsa', '2rb', 3145, 4000, 999, '2026-01-22 11:24:17', NULL),
(154, 13, 'Pulsa', '3rb', 3943, 5000, 999, '2026-01-22 11:24:17', NULL),
(155, 13, 'Pulsa', '4rb', 4944, 6000, 999, '2026-01-22 11:24:17', NULL),
(156, 13, 'Pulsa', '5rb + 7 Hari+-', 5373, 7000, 999, '2026-01-22 11:24:17', NULL),
(157, 13, 'Pulsa', '10rb + 15 Hari+-', 10349, 12000, 999, '2026-01-22 11:24:17', NULL),
(158, 13, 'Pulsa', '15rb', 14965, 17000, 999, '2026-01-22 11:24:17', NULL),
(159, 13, 'Pulsa', '20rb + 30 Hari+-', 20015, 22000, 999, '2026-01-22 11:24:17', NULL),
(160, 13, 'Pulsa', '25rb + 30 Hari+-', 24893, 27000, 999, '2026-01-22 11:24:17', NULL),
(161, 13, 'Pulsa', '30rb', 29900, 32000, 999, '2026-01-22 11:24:17', NULL),
(162, 13, 'Pulsa', '35rb', 34822, 37000, 999, '2026-01-22 11:24:17', NULL),
(163, 13, 'Pulsa', '40rb', 39575, 42000, 999, '2026-01-22 11:24:17', NULL),
(164, 13, 'Pulsa', '45rb', 44870, 47000, 999, '2026-01-22 11:24:17', NULL),
(165, 13, 'Pulsa', '50rb + 45 Hari+-', 49715, 52000, 999, '2026-01-22 11:24:17', NULL),
(166, 13, 'Pulsa', '55rb + 45 Hari+-', 54729, 57000, 999, '2026-01-22 11:24:17', NULL),
(167, 13, 'Pulsa', '60rb', 59702, 62000, 999, '2026-01-22 11:24:17', NULL),
(168, 13, 'Pulsa', '65rb', 64635, 67000, 999, '2026-01-22 11:24:17', NULL),
(169, 13, 'Pulsa', '70rb', 69637, 72000, 9999, '2026-01-22 11:24:17', NULL),
(170, 13, 'Pulsa', '75rb', 74100, 77000, 999, '2026-01-22 11:24:17', NULL),
(171, 13, 'Pulsa', '80rb', 79467, 82000, 999, '2026-01-22 11:24:17', NULL),
(172, 13, 'Pulsa', '85rb', 84360, 87000, 999, '2026-01-22 11:24:17', NULL),
(173, 13, 'Pulsa', '90rb', 89435, 92000, 999, '2026-01-22 11:24:17', NULL),
(174, 13, 'Pulsa', '95rb', 94400, 97000, 999, '2026-01-22 11:24:17', NULL),
(175, 13, 'Pulsa', '100rb', 98200, 102000, 999, '2026-01-22 11:24:17', NULL),
(176, 14, 'Pulsa', '5rb', 6090, 7000, 999, '2026-01-22 11:33:40', NULL),
(177, 14, 'Pulsa', '10rb', 10395, 12000, 999, '2026-01-22 11:33:40', NULL),
(178, 14, 'Pulsa', '15rb', 14799, 17000, 999, '2026-01-22 11:33:40', NULL),
(179, 14, 'Pulsa', '20rb', 20280, 22000, 999, '2026-01-22 11:33:40', NULL),
(180, 14, 'Pulsa', '25rb', 24762, 27000, 999, '2026-01-22 11:33:40', NULL),
(181, 14, 'Pulsa', '30rb', 29915, 32000, 999, '2026-01-22 11:33:40', NULL),
(182, 14, 'Pulsa', '40rb', 40125, 42000, 999, '2026-01-22 11:33:40', NULL),
(183, 14, 'Pulsa', '50rb', 49145, 52000, 999, '2026-01-22 11:33:40', NULL),
(184, 14, 'Pulsa', '60rb', 58179, 62000, 999, '2026-01-22 11:33:40', NULL),
(185, 14, 'Pulsa', '70rb', 68325, 72000, 999, '2026-01-22 11:33:40', NULL),
(186, 14, 'Pulsa', '75rb', 74145, 77000, 999, '2026-01-22 11:33:40', NULL),
(187, 14, 'Pulsa', '100rb', 95670, 98000, 999, '2026-01-22 11:33:40', NULL),
(188, 15, 'Pulsa', '5rb', 5225, 7000, 999, '2026-01-22 11:42:48', NULL),
(189, 15, 'Pulsa', '10rb', 10127, 12000, 999, '2026-01-22 11:42:48', NULL),
(190, 15, 'Pulsa', '12rb', 12125, 14000, 999, '2026-01-22 11:42:48', NULL),
(191, 15, 'Pulsa', '15rb', 14950, 17000, 999, '2026-01-22 11:42:48', NULL),
(192, 15, 'Pulsa', '20rb', 20080, 22000, 999, '2026-01-22 11:42:48', NULL),
(193, 15, 'Pulsa', '25rb', 25145, 27000, 999, '2026-01-22 11:42:48', NULL),
(194, 15, 'Pulsa', '30rb', 29705, 32000, 999, '2026-01-22 11:42:48', NULL),
(195, 15, 'Pulsa', '40rb', 40038, 42000, 999, '2026-01-22 11:42:48', NULL),
(196, 15, 'Pulsa', '50rb', 50438, 52000, 999, '2026-01-22 11:42:48', NULL),
(197, 15, 'Pulsa', '60rb', 59750, 62000, 999, '2026-01-22 11:42:48', NULL),
(198, 15, 'Pulsa', '65rb', 65925, 67000, 999, '2026-01-22 11:42:48', NULL),
(199, 15, 'Pulsa', '70rb', 69626, 72000, 999, '2026-01-22 11:42:48', NULL),
(200, 15, 'Pulsa', '75rb', 74710, 77000, 999, '2026-01-22 11:42:48', NULL),
(201, 15, 'Pulsa', '80rb', 80250, 82000, 999, '2026-01-22 11:42:48', NULL),
(202, 15, 'Pulsa', '85rb', 85245, 87000, 999, '2026-01-22 11:42:48', NULL),
(203, 15, 'Pulsa', '90rb', 90205, 92000, 999, '2026-01-22 11:42:48', NULL),
(204, 15, 'Pulsa', '95rb', 95165, 97000, 999, '2026-01-22 11:42:48', NULL),
(205, 15, 'Pulsa', '100rb', 100455, 102000, 999, '2026-01-22 11:42:48', NULL),
(206, 16, 'Pulsa', '5rb', 6040, 7000, 999, '2026-01-22 11:51:41', NULL),
(207, 16, 'Pulsa', '10rb', 10775, 12000, 999, '2026-01-22 11:51:41', NULL),
(208, 16, 'Pulsa', '15rb', 15138, 17000, 999, '2026-01-22 11:51:41', NULL),
(209, 16, 'Pulsa', '25rb', 25115, 27000, 999, '2026-01-22 11:51:41', NULL),
(210, 16, 'Pulsa', '30rb', 29900, 32000, 999, '2026-01-22 11:51:41', NULL),
(211, 16, 'Pulsa', '40rb', 40174, 42000, 999, '2026-01-22 11:51:41', NULL),
(212, 16, 'Pulsa', '50rb', 50205, 52000, 999, '2026-01-22 11:51:41', NULL),
(213, 16, 'Pulsa', '60rb', 60165, 62000, 999, '2026-01-22 11:51:41', NULL),
(214, 16, 'Pulsa', '70rb', 70165, 72000, 999, '2026-01-22 11:51:41', NULL),
(215, 16, 'Pulsa', '80rb', 80175, 82000, 999, '2026-01-22 11:51:41', NULL),
(216, 16, 'Pulsa', '90rb', 90118, 92000, 999, '2026-01-22 11:51:41', NULL),
(217, 16, 'Pulsa', '100rb', 100334, 102000, 999, '2026-01-22 11:51:41', NULL),
(218, 16, 'Pulsa', '300rb', 300000, 302000, 999, '2026-01-22 11:51:41', NULL),
(219, 17, 'Pulsa', '5rb', 5369, 7000, 999, '2026-01-22 13:47:32', NULL),
(220, 17, 'Pulsa', '10rb', 10273, 12000, 999, '2026-01-22 13:59:28', NULL),
(221, 17, 'Pulsa', '15rb', 15075, 17000, 999, '2026-01-22 13:59:28', NULL),
(222, 17, 'Pulsa', '20rb', 20180, 22000, 999, '2026-01-22 13:59:28', NULL),
(223, 17, 'Pulsa', '25rb', 24910, 27000, 999, '2026-01-22 13:59:28', NULL),
(224, 17, 'Pulsa', '30rb', 30050, 32000, 999, '2026-01-22 13:59:28', NULL),
(225, 17, 'Pulsa', '35rb', 34814, 37000, 999, '2026-01-22 13:59:28', NULL),
(226, 17, 'Pulsa', '40rb', 40065, 42000, 999, '2026-01-22 13:59:28', NULL),
(227, 17, 'Pulsa', '45rb', 44715, 47000, 999, '2026-01-22 13:59:28', NULL),
(228, 17, 'Pulsa', '50rb', 50010, 52000, 999, '2026-01-22 13:59:28', NULL),
(229, 17, 'Pulsa', '55rb', 54760, 57000, 999, '2026-01-22 13:59:28', NULL),
(230, 17, 'Pulsa', '60rb', 59685, 62000, 999, '2026-01-22 13:59:28', NULL),
(231, 17, 'Pulsa', '65rb', 64560, 67000, 999, '2026-01-22 13:59:28', NULL),
(232, 17, 'Pulsa', '70rb', 69535, 72000, 999, '2026-01-22 13:59:28', NULL),
(233, 17, 'Pulsa', '75rb', 73400, 77000, 999, '2026-01-22 13:59:28', NULL),
(234, 17, 'Pulsa', '80rb', 79410, 82000, 999, '2026-01-22 13:59:28', NULL),
(235, 17, 'Pulsa', '85rb', 84360, 87000, 999, '2026-01-22 13:59:28', NULL),
(236, 17, 'Pulsa', '90rb', 89235, 92000, 999, '2026-01-22 13:59:28', NULL),
(237, 17, 'Pulsa', '95rb', 94210, 97000, 999, '2026-01-22 13:59:28', NULL),
(238, 17, 'Pulsa', '100rb', 99575, 102000, 999, '2026-01-22 13:59:28', NULL),
(239, 18, 'Pulsa', '5rb', 6090, 7000, 999, '2026-01-22 14:11:31', NULL),
(240, 18, 'Pulsa', '10rb', 11049, 12000, 999, '2026-01-22 14:11:31', NULL),
(241, 18, 'Pulsa', '15rb', 15172, 17000, 999, '2026-01-22 14:11:31', NULL),
(242, 18, 'Pulsa', '25rb', 25125, 27000, 999, '2026-01-22 14:11:31', NULL),
(243, 18, 'Pulsa', '30rb', 30335, 32000, 999, '2026-01-22 14:11:31', NULL),
(244, 18, 'Pulsa', '40rb', 40174, 42000, 999, '2026-01-22 14:11:31', NULL),
(245, 18, 'Pulsa', '50rb', 50290, 52000, 999, '2026-01-22 14:11:31', NULL),
(246, 18, 'Pulsa', '60rb', 60145, 62000, 999, '2026-01-22 14:11:31', NULL),
(247, 18, 'Pulsa', '70rb', 70085, 72000, 999, '2026-01-22 14:11:31', NULL),
(248, 18, 'Pulsa', '80rb', 79995, 82000, 999, '2026-01-22 14:11:31', NULL),
(249, 18, 'Pulsa', '90rb', 90118, 92000, 999, '2026-01-22 14:11:31', NULL),
(250, 18, 'Pulsa', '100rb', 99645, 102000, 999, '2026-01-22 14:11:31', NULL),
(251, 19, 'Voucher Kuota', 'Voucher Kuota 1,5GB 3 Hari', 9410, 10500, 999, '2026-01-22 14:25:57', NULL),
(252, 19, 'Voucher Kuota', 'Voucher Kuota 2GB 3 Hari', 12175, 13500, 999, '2026-01-22 14:25:57', NULL),
(253, 19, 'Voucher Kuota', 'Voucher Kuota 2,5GB 5 Hari', 13145, 14500, 999, '2026-01-22 14:25:57', NULL),
(254, 19, 'Voucher Kuota', 'Voucher Kuota 3GB 3 Hari', 13475, 14500, 999, '2026-01-22 14:25:57', NULL),
(255, 19, 'Voucher Kuota', 'Voucher Kuota  3GB 5 Hari', 14695, 16000, 999, '2026-01-22 14:25:57', NULL),
(256, 19, 'Voucher Kuota', 'Voucher Kuota 3,5GB 7 Hari', 20350, 21500, 999, '2026-01-22 14:25:57', NULL),
(257, 19, 'Voucher Kuota', 'Voucher Kuota 4,5GB 5 Hari', 20750, 22000, 999, '2026-01-22 14:25:57', NULL),
(258, 19, 'Voucher Kuota', 'Voucher Kuota 5,5GB 5 Hari', 24395, 25500, 999, '2026-01-22 14:25:57', NULL),
(259, 19, 'Voucher Kuota', 'Voucher Kuota 7GB 7 Hari', 28725, 30000, 999, '2026-01-22 14:25:57', NULL),
(260, 19, 'Voucher Kuota', 'Voucher Kuota 10GB 7 Hari', 37050, 38500, 999, '2026-01-22 14:25:57', NULL),
(261, 20, 'Wifi.id', 'Vcr WiFi.id 5.000,1Hr', 5050, 51500, 999, '2026-01-22 14:35:52', NULL),
(262, 20, 'Wifi.id', 'Vcr WiFi.id 20.000,7Hr', 20050, 21500, 999, '2026-01-22 14:35:52', NULL),
(263, 20, 'Wifi.id', 'Vcr WiFi.id 50.000,30Hr', 47575, 48500, 999, '2026-01-22 14:35:52', NULL),
(264, 21, 'Paket FTV 1 (Free To View)', 'Paket FTV 1 (Free To View)', 49849, 53000, 999, '2026-01-23 07:57:19', NULL),
(265, 21, 'Paket Liga Inggris 1 Bulan', 'Paket Liga Inggris 1 Bulan', 170888, 174000, 999, '2026-01-23 07:57:19', NULL),
(266, 21, 'Paket All Channel 1 Bulan', 'Paket All Channel 1 Bulan', 189625, 192000, 999, '2026-01-23 07:57:19', NULL),
(267, 21, 'Paket All Channel 3 Bulan', 'Paket All Channel 3 Bulan', 460950, 464000, 999, '2026-01-23 07:57:19', NULL),
(268, 22, 'Paket Basic 1 Bulan', 'Paket Basic 1 Bulan', 20957, 23000, 999, '2026-01-23 08:02:07', NULL),
(269, 22, 'Paket Basic 3 Bulan', 'Paket Basic 3 Bulan', 44930, 47000, 999, '2026-01-23 08:02:07', NULL),
(270, 22, 'Promo Kilat Diamond 1Bulan', 'Promo Kilat Diamond 1Bulan', 49700, 52000, 999, '2026-01-23 08:02:07', NULL),
(271, 22, 'Paket Liga1 BRI 1 Bulan', 'Paket Liga1 BRI 1 Bulan', 68095, 70000, 999, '2026-01-23 08:02:07', NULL),
(272, 22, 'Paket Basic 6 Bulan', 'Paket Basic 6 Bulan', 77610, 79000, 999, '2026-01-23 08:02:07', NULL),
(273, 22, 'Paket HBO + Champions 30 Hari', 'Paket HBO + Champions 30 Hari', 78040, 80000, 999, '2026-01-23 08:02:07', NULL),
(274, 22, 'Paket HBO + Liga Indonesia 30 Hari', 'Paket HBO + Liga Indonesia 30 Hari', 78040, 81000, 999, '2026-01-23 08:02:07', NULL),
(275, 23, 'TRANSVISION ALL TIPE DECODER', 'CHANNEL JOWO - 1 BULAN', 10475, 12000, 999, '2026-01-23 08:07:32', NULL),
(276, 23, 'GOLD - 1 BULAN', 'GOLD - 1 BULAN', 18212, 20000, 999, '2026-01-23 08:07:32', NULL),
(277, 23, 'CHANNEL JOWO - 3 BULAN', 'CHANNEL JOWO - 3 BULAN', 20350, 22500, 999, '2026-01-23 08:07:32', NULL),
(278, 23, 'NUSA HIBURAN - 1 BULAN', 'NUSA HIBURAN - 1 BULAN', 26265, 28000, 999, '2026-01-23 08:07:32', NULL),
(279, 23, 'GOLD - 3 BULAN', 'GOLD - 3 BULAN', 40337, 43000, 999, '2026-01-23 08:07:32', NULL),
(280, 23, 'PLATINUM - 1 BULAN', 'PLATINUM - 1 BULAN', 43085, 44500, 999, '2026-01-23 08:07:32', NULL),
(281, 23, 'HBO - 1 BULAN', 'HBO - 1 BULAN', 69283, 71000, 999, '2026-01-23 08:07:32', NULL),
(282, 23, 'DIAMOND - 1 BULAN', 'DIAMOND - 1 BULAN', 79070, 82000, 999, '2026-01-23 08:07:32', NULL),
(283, 24, 'XSTREAM SILVER - 1 BULAN', 'XSTREAM SILVER - 1 BULAN', 29560, 32000, 999, '2026-01-23 08:09:57', NULL),
(284, 24, 'XSTREAM GOLD - 1 BULAN', 'XSTREAM GOLD - 1 BULAN', 49500, 52000, 999, '2026-01-23 08:09:57', NULL),
(285, 24, 'XSTREAM PLATINUM - 1 BULAN', 'XSTREAM PLATINUM - 1 BULAN', 96500, 98000, 999, '2026-01-23 08:09:57', NULL),
(286, 24, 'XSTREAM DIAMOND - 1 BULAN ', 'XSTREAM DIAMOND - 1 BULAN ', 181100, 183000, 999, '2026-01-23 08:09:57', NULL),
(290, 29, 'Robux', '80 Robux', 13988, 15500, 999, '2026-01-24 10:14:48', NULL),
(292, 29, 'Robux', '160 Robux', 27971, 29000, 999, '2026-01-25 06:35:48', NULL),
(294, 29, 'Robux', '240 Robux', 41918, 44000, 999, '2026-01-25 07:27:35', NULL),
(295, 29, 'Robux', '320 Robux', 55866, 59000, 999, '2026-01-25 07:27:35', NULL),
(296, 29, 'Robux', '500 Robux', 69814, 72500, 999, '2026-01-25 07:27:35', NULL),
(297, 11, 'Diamonds', '261 Diamonds', 67870, 69500, 999, '2026-01-25 10:38:17', NULL),
(298, 11, 'Diamonds', '275 Diamonds', 71624, 73500, 999, '2026-01-25 10:38:17', NULL),
(299, 11, 'Diamonds', '277 Diamonds', 71660, 73500, 999, '2026-01-25 10:38:17', NULL),
(300, 11, 'Diamonds', '278 Diamonds', 72030, 74000, 999, '2026-01-25 10:38:17', NULL),
(301, 11, 'Diamonds', '281 Diamonds', 72503, 74500, 999, '2026-01-25 10:38:17', NULL),
(302, 11, 'Diamonds', '280 Diamonds', 72522, 74500, 999, '2026-01-25 10:38:17', NULL),
(303, 11, 'Diamonds', '282 Diamonds', 73081, 75000, 999, '2026-01-25 10:38:17', NULL),
(304, 11, 'Diamonds', '284 Diamonds', 73473, 76500, 999, '2026-01-25 10:38:17', NULL),
(305, 11, 'Diamonds', '296 Diamonds', 76089, 77500, 999, '2026-01-25 10:38:17', NULL),
(306, 11, 'Diamonds', '300 Diamonds', 77510, 78500, 999, '2026-01-25 10:38:17', NULL),
(307, 11, 'Diamonds', '305 Diamonds', 78929, 81000, 999, '2026-01-25 10:38:17', NULL),
(308, 11, 'Diamonds', '323 Diamonds', 83445, 84500, 999, '2026-01-25 10:38:17', NULL),
(309, 11, 'Diamonds', '332 Diamonds', 85606, 87000, 999, '2026-01-25 10:38:17', NULL),
(310, 11, 'Diamonds', '336 Diamonds', 86828, 88000, 999, '2026-01-25 10:38:17', NULL),
(311, 11, 'Diamonds', '340 Diamonds', 87454, 88500, 999, '2026-01-25 10:38:17', NULL),
(312, 11, 'Diamonds', '344  Diamonds', 88770, 90000, 999, '2026-01-25 10:38:17', NULL),
(313, 11, 'Diamonds', '345 Diamonds', 88875, 90000, 999, '2026-01-25 10:38:17', NULL),
(314, 11, 'Diamonds', '350 Diamonds', 90364, 91500, 999, '2026-01-25 10:38:17', NULL),
(315, 11, 'Diamonds', '355 Diamonds', 91243, 92500, 999, '2026-01-25 10:38:17', NULL),
(316, 11, 'Diamonds', '372 Diamonds', 95932, 97000, 999, '2026-01-25 10:38:17', NULL),
(317, 11, 'Diamonds', '373 Diamonds', 95932, 97000, 999, '2026-01-25 10:38:17', NULL),
(318, 11, 'Diamonds', '381 Diamonds', 97873, 99000, 999, '2026-01-25 10:38:17', NULL),
(319, 11, 'Diamonds', '383 Diamonds', 98735, 100000, 999, '2026-01-25 10:38:17', NULL),
(320, 11, 'Diamonds', '388 Diamonds', 99686, 101500, 999, '2026-01-25 10:38:17', NULL),
(321, 11, 'Diamonds', '395 Diamonds', 102384, 103500, 999, '2026-01-25 10:38:17', NULL),
(322, 11, 'Diamonds', '399 Diamonds', 103336, 104500, 999, '2026-01-25 10:38:17', NULL),
(323, 11, 'Diamonds', '406 Diamonds', 105149, 106500, 999, '2026-01-25 10:38:17', NULL),
(324, 11, 'Diamonds', '408 Diamonds', 105594, 107000, 999, '2026-01-27 02:40:46', NULL),
(325, 11, 'Diamonds', '415 Diamonds', 107961, 109000, 999, '2026-01-27 02:40:46', NULL),
(326, 11, 'Diamonds', '416 Diamonds', 107989, 109500, 999, '2026-01-27 02:40:46', NULL),
(327, 11, 'Diamonds', '425 Diamonds', 109943, 111000, 999, '2026-01-27 02:40:46', NULL),
(328, 11, 'Diamonds', '429 Diamonds', 110787, 113000, 999, '2026-01-27 02:40:46', NULL),
(329, 11, 'Diamonds', '440 Diamonds', 113731, 115000, 999, '2026-01-27 02:40:46', NULL),
(330, 11, 'Diamonds', '444 Diamonds', 114473, 115500, 999, '2026-01-27 02:40:46', NULL),
(331, 11, 'Diamonds', '448 Diamonds', 115894, 117000, 9999, '2026-01-27 02:40:46', NULL),
(332, 11, 'Diamonds', '453 Diamonds', 117313, 118500, 999, '2026-01-27 02:40:46', NULL),
(333, 11, 'Diamonds', '460 Diamonds', 118838, 120000, 999, '2026-01-27 02:40:46', NULL),
(334, 11, 'Diamonds', '500 Diamonds', 129153, 130500, 999, '2026-01-27 02:48:45', NULL),
(335, 11, 'Diamonds', '509 Diamonds', 131600, 133000, 999, '2026-01-27 02:48:45', NULL),
(336, 11, 'Diamonds', '514 Diamonds', 132571, 133500, 999, '2026-01-27 02:48:45', NULL),
(337, 11, 'Diamonds', '516 Diamonds', 133313, 134500, 999, '2026-01-27 02:48:45', NULL),
(338, 11, 'Diamonds', '518 Diamonds', 133313, 134500, 999, '2026-01-27 02:48:45', NULL),
(339, 11, 'Diamonds', '530 Diamonds', 136628, 138000, 999, '2026-01-27 02:48:45', NULL),
(340, 11, 'Diamonds', '550 Diamonds', 143470, 144500, 999, '2026-01-27 02:48:45', NULL),
(341, 11, 'Diamonds', '554 Diamonds', 144469, 145500, 999, '2026-01-27 02:48:45', NULL),
(342, 11, 'Diamonds', '600 Diamonds', 152050, 153500, 999, '2026-01-27 02:48:45', NULL),
(343, 11, 'Diamonds', '635 Diamonds', 162653, 164000, 999, '2026-01-27 02:48:45', NULL),
(344, 11, 'Diamonds', '642 Diamonds', 163949, 165000, 999, '2026-01-27 02:48:45', NULL),
(345, 11, 'Diamonds', '659 Diamonds', 168707, 170000, 999, '2026-01-27 02:48:45', NULL),
(346, 29, 'Robux', '1000 Robux', 139627, 141000, 999, '2026-01-27 02:52:40', NULL),
(347, 29, 'Robux', '1500 Robux', 209416, 210500, 999, '2026-01-27 02:52:40', NULL),
(348, 29, 'Robux', '2000 Robux', 279154, 280500, 999, '2026-01-27 02:52:40', NULL),
(349, 29, 'Robux', '1700 Robux', 279154, 280500, 999, '2026-01-27 02:52:40', NULL),
(350, 29, 'Robux', '2500 Robux', 348943, 351000, 999, '2026-01-27 02:52:40', NULL),
(351, 32, 'Premium', '1 Day 2 User', 2000, 5000, 99, '2026-01-27 03:01:57', NULL),
(352, 32, 'Premium', '1 Week 2 User', 8000, 10000, 9, '2026-01-27 03:01:57', NULL),
(353, 32, 'Premium', '1 Month 2 User', 14000, 18000, 9, '2026-01-27 03:01:57', NULL),
(354, 32, 'Premium', '1 Month 2 User STRONG', 15000, 19500, 9, '2026-01-27 03:01:57', NULL),
(355, 32, 'Premium', '1 Day 1 User', 3000, 6000, 9, '2026-01-27 03:01:57', NULL),
(356, 32, 'Premium', '1 Week 1 User', 10000, 14000, 9, '2026-01-27 03:01:57', NULL),
(357, 32, 'Premium', '1 Month 1 User', 22000, 24000, 9, '2026-01-27 03:01:57', NULL),
(358, 32, 'Premium', '1 Month 1 User STRONG', 24000, 27000, 9, '2026-01-27 03:01:57', NULL),
(359, 32, 'Premium', '1 Day Semiprivate', 5000, 10500, 9, '2026-01-27 03:01:57', NULL),
(360, 32, 'Premium', '1 Week Semiprivate', 13000, 16500, 9, '2026-01-27 03:01:57', NULL),
(361, 32, 'Premium', '1 Month Semiprivate', 25000, 28500, 9, '2026-01-27 03:01:57', NULL),
(362, 32, 'Premium', '1 Month Semiprivate STRONG', 28000, 33000, 9, '2026-01-27 03:01:57', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_logs`
--

CREATE TABLE `stok_logs` (
  `id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `perubahan` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$0z7bDg.JLN6RYN7wRLNYGOguT0NiqxzLC.2kj0K4PfpLhZv5DXwp2', 'admin', '2025-12-24 08:57:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `kode_voucher` varchar(512) NOT NULL,
  `nama_voucher` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tipe_diskon` enum('PERSEN','RUPIAH') NOT NULL DEFAULT 'PERSEN',
  `nilai_diskon` decimal(10,2) NOT NULL,
  `min_pembelian` decimal(10,2) DEFAULT 0.00,
  `max_diskon` decimal(10,2) DEFAULT NULL COMMENT 'Max diskon untuk tipe PERSEN',
  `kuota_total` int(11) DEFAULT NULL COMMENT 'NULL = unlimited',
  `kuota_terpakai` int(11) DEFAULT 0,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_berakhir` datetime NOT NULL,
  `status` enum('AKTIF','NONAKTIF') DEFAULT 'AKTIF',
  `berlaku_untuk` text DEFAULT NULL COMMENT 'JSON: ["ALL"] atau ["10","11","12"] (game_ids)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `vouchers`
--

INSERT INTO `vouchers` (`id`, `kode_voucher`, `nama_voucher`, `deskripsi`, `tipe_diskon`, `nilai_diskon`, `min_pembelian`, `max_diskon`, `kuota_total`, `kuota_terpakai`, `tanggal_mulai`, `tanggal_berakhir`, `status`, `berlaku_untuk`, `created_at`, `updated_at`) VALUES
(9, 'EPEPDIDADA', 'Freefire 1 Orang', '', 'PERSEN', 10.00, 0.00, NULL, 3, 1, '2026-06-26 07:24:00', '2026-08-27 07:24:00', 'AKTIF', '[\"ALL\"]', '2026-02-20 07:24:18', '2026-06-26 00:05:43'),
(10, 'EPEPDIDADA1', 'EPEPDIDADA1', '', 'PERSEN', 100.00, 0.00, NULL, 1, 0, '2026-06-26 00:06:00', '2026-07-26 00:06:00', 'AKTIF', '[\"ALL\"]', '2026-06-26 00:06:21', '2026-06-26 00:06:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `voucher_usage`
--

CREATE TABLE `voucher_usage` (
  `id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_identifier` varchar(100) DEFAULT NULL COMMENT 'Phone number atau session ID',
  `diskon_amount` decimal(10,2) NOT NULL,
  `harga_sebelum_diskon` decimal(10,2) NOT NULL,
  `harga_setelah_diskon` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `voucher_usage`
--

INSERT INTO `voucher_usage` (`id`, `voucher_id`, `order_id`, `user_identifier`, `diskon_amount`, `harga_sebelum_diskon`, `harga_setelah_diskon`, `created_at`) VALUES
(2, 9, 73, 'TEST USER', 3300.00, 33000.00, 29700.00, '2026-02-20 07:39:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `game_fields`
--
ALTER TABLE `game_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resi` (`resi`),
  ADD UNIQUE KEY `resi_2` (`resi`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeks untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeks untuk tabel `stok_logs`
--
ALTER TABLE `stok_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_voucher` (`kode_voucher`);

--
-- Indeks untuk tabel `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_id` (`voucher_id`),
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `game_fields`
--
ALTER TABLE `game_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT untuk tabel `stok_logs`
--
ALTER TABLE `stok_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `voucher_usage`
--
ALTER TABLE `voucher_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `game_fields`
--
ALTER TABLE `game_fields`
  ADD CONSTRAINT `game_fields_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stok_logs`
--
ALTER TABLE `stok_logs`
  ADD CONSTRAINT `stok_logs_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `voucher_usage`
--
ALTER TABLE `voucher_usage`
  ADD CONSTRAINT `voucher_usage_ibfk_1` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_usage_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
