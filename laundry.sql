-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 02, 2023 at 05:23 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_01_23_205130_create_reset_code_passwords_table', 1),
(6, '2023_01_27_113722_create_orders_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` enum('new','pending','started','finished') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'new',
  `readable` tinyint(1) NOT NULL DEFAULT '0',
  `acceptable` tinyint(1) NOT NULL DEFAULT '0',
  `canceled_from` enum('grille','user') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT '0',
  `reason_cancellation` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `grille_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_grille_id_foreign` (`grille_id`),
  KEY `orders_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `status`, `readable`, `acceptable`, `canceled_from`, `canceled`, `reason_cancellation`, `grille_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'started', 0, 0, 'user', 1, NULL, 1, 2, '2023-01-27 11:53:02', '2023-01-27 12:31:04'),
(2, 'pending', 0, 1, 'user', 0, NULL, 4, 2, '2023-01-27 11:57:22', '2023-01-27 17:55:48'),
(3, 'finished', 0, 0, 'grille', 0, NULL, 4, 2, '2023-01-27 12:52:52', '2023-01-27 14:33:57'),
(4, 'finished', 0, 1, 'user', 0, NULL, 4, 2, '2023-01-27 13:04:07', '2023-01-31 17:49:19'),
(5, 'new', 0, 0, 'user', 0, NULL, 4, 2, '2023-01-27 13:05:56', '2023-01-27 13:11:42'),
(6, 'new', 0, 0, 'user', 1, 'asd', 4, 2, '2023-01-27 13:11:45', '2023-01-27 13:12:02'),
(7, 'new', 0, 0, 'user', 1, 'asd', 4, 2, '2023-01-27 13:12:04', '2023-01-27 13:15:20'),
(8, 'new', 0, 0, 'user', 1, 'asd', 4, 2, '2023-01-27 13:15:23', '2023-01-27 13:15:26'),
(9, 'finished', 0, 0, 'grille', 1, 'asd', 4, 2, '2023-01-27 13:15:48', '2023-01-27 14:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb3_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(3, 'App\\Models\\User', 3, 'API TOKEN', 'dca34c55aaeea3aab7cf2c9caf41c40728301375dcaebe2ec0b0226ff6b1f0e0', '[\"*\"]', NULL, NULL, '2023-01-27 11:54:21', '2023-01-27 11:54:21'),
(4, 'App\\Models\\User', 4, 'API TOKEN', '9bf3d9ef60a3af1d37e1ffc319b39908f77609f73671ba0fea2555591a9bdff4', '[\"*\"]', NULL, NULL, '2023-01-27 11:54:34', '2023-01-27 11:54:34'),
(5, 'App\\Models\\User', 5, 'API TOKEN', 'b5c5446b5ac93dc3abdf5bb12d6430151e0b3e8dba977370876adade04f988fb', '[\"*\"]', NULL, NULL, '2023-01-27 11:54:53', '2023-01-27 11:54:53'),
(6, 'App\\Models\\User', 6, 'API TOKEN', 'c0bdaac021e1395dd7e2a2fdee4a7477a9492f87373fe867b39309187dce89e4', '[\"*\"]', NULL, NULL, '2023-01-27 11:55:04', '2023-01-27 11:55:04'),
(10, 'App\\Models\\User', 4, 'PostmanRuntime/7.30.0', '2faad270eab94f827b80064c3c688989ebfe49303c385d09039a5e48c1d2c61a', '[\"*\"]', '2023-02-02 14:10:15', NULL, '2023-01-31 17:44:08', '2023-02-02 14:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `reset_code_passwords`
--

DROP TABLE IF EXISTS `reset_code_passwords`;
CREATE TABLE IF NOT EXISTS `reset_code_passwords` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `icc_phone` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `role` enum('admin','grille','user') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'user',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `location` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `icc_phone`, `phone`, `role`, `status`, `location`, `latitude`, `longitude`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Alaa Mubarak', 'alaa@laundry.com', '+970', '594126446', 'grille', 1, 'asd', '-90.5656', '40.0565', NULL, '$2y$10$flmMKvymRyYjxTgtOjITW.KE1iBM8Q41ScZYF7I5hUDX11yC5.lMu', NULL, '2023-01-27 10:37:39', '2023-01-27 10:37:39'),
(2, 'Baraa Mubarak 2', 'baraa@laundry.com', '+970', '594126449', 'grille', 1, 'asdasd', '-90.5656', '40.0565', NULL, '$2y$10$X.L7mfc2QFfud6Ja5Q1FXeXzj34Zey8uSAH0N.5dr1TRDTVkCfW3y', NULL, '2023-01-27 11:37:58', '2023-01-27 12:51:21'),
(3, 'Alaa Mubarak', 'alaa1@laundry.com', '+970', '594126441', 'grille', 1, 'asd', '-90.5656', '40.0565', NULL, '$2y$10$WscJrELEeyujAzu5eQQmZupkRDqVSAdxyLm.Wjz5rLDPn4AQ.tiqm', NULL, '2023-01-27 11:54:21', '2023-01-27 11:54:21'),
(4, 'Alaa Mubarak', 'alaa2@laundry.com', '+970', '594126442', 'grille', 1, 'asd', '-90.5656', '40.0565', NULL, '$2y$10$N50Ds0IiEzoh33oWeZbgouDPCC8sej8ak2/Ef9ewYqPoeG0IhM/zi', NULL, '2023-01-27 11:54:34', '2023-01-27 11:54:34'),
(5, 'Baraa Mubarak', 'baraa1@laundry.com', '+970', '594126142', 'user', 1, 'asd', '-90.5656', '40.0565', NULL, '$2y$10$3VcWk3FQBsSu/YmN3./0nOnvjUPCISximd7DTyeIMalWEPwrJCUiy', NULL, '2023-01-27 11:54:53', '2023-01-27 11:54:53'),
(6, 'Baraa Mubarak', 'baraa2@laundry.com', '+970', '594122142', 'user', 1, 'asd', '-90.5656', '40.0565', NULL, '$2y$10$XuCeXvP.HOKpCv8J7gtPi.9S1U8KDfTQN3ZsHXJ.7a.36//GlbhcC', NULL, '2023-01-27 11:55:04', '2023-01-27 11:55:04');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
