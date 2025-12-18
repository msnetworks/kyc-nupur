-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 15, 2024 at 11:06 AM
-- Server version: 10.11.8-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u681354062_verification`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `parent_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@example.com', 'superadmin', 1, NULL, '$2y$10$NLMH1tnsbz.tT6gcI/Cfy.Wc6gmZm02ThG.6aVH9J0.f7nVmoiHR2', NULL, '2024-08-05 15:29:11', '2024-08-05 15:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `application_types`
--

CREATE TABLE `application_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_types`
--

INSERT INTO `application_types` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Applicant', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(2, 'Co-Applicant', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(3, 'Guranter', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(4, 'Seller', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch_code` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `branch_code`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(2, 'HDFC', 'HDFC', '0', 1, '2024-08-06 05:56:30', 1, '2024-08-06 05:56:30'),
(3, 'ICICI', 'ICICI', '0', 1, '2024-08-06 05:56:55', 1, '2024-08-06 05:56:55'),
(4, 'testing', 'testing', '0', 1, '2024-08-08 10:43:34', 1, '2024-08-08 10:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `bank_product_mappings`
--

CREATE TABLE `bank_product_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_product_mappings`
--

INSERT INTO `bank_product_mappings` (`id`, `bank_id`, `product_id`, `created_at`, `updated_at`) VALUES
(3, 2, 1, '2024-08-06 05:56:30', '2024-08-06 05:56:30'),
(4, 2, 2, '2024-08-06 05:56:30', '2024-08-06 05:56:30'),
(5, 2, 3, '2024-08-06 05:56:30', '2024-08-06 05:56:30'),
(6, 2, 5, '2024-08-06 05:56:30', '2024-08-06 05:56:30'),
(7, 3, 3, '2024-08-06 05:56:55', '2024-08-06 05:56:55'),
(8, 3, 5, '2024-08-06 05:56:55', '2024-08-06 05:56:55'),
(9, 4, 1, '2024-08-08 10:43:34', '2024-08-08 10:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_type` varchar(255) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `refrence_number` varchar(255) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `vehicle` varchar(255) NOT NULL,
  `co_applicant_name` varchar(255) NOT NULL,
  `guarantee_name` varchar(255) NOT NULL,
  `geo_limit` varchar(255) NOT NULL,
  `tat_time` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '''0->inprogress,1->resolve, 2->verified, 3->rejected''',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `application_type`, `bank_id`, `product_id`, `refrence_number`, `applicant_name`, `amount`, `vehicle`, `co_applicant_name`, `guarantee_name`, `geo_limit`, `tat_time`, `remarks`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(18, '2', 2, 3, 'EP0104534', 'Susheel Sahoo', 100000.00, 'Honda', 'Joyti Sahu', 'Ankit Sahu', 'Local', '01:10', 'Test Remark', '0', 1, 1, '2024-08-14 12:16:42', '2024-08-14 12:16:42'),
(19, '2', 4, 1, 'EP0101044', 'Susheel Sahoo', 10004.00, 'Honda', 'Joyti Sahu', 'Ankit Sahu', 'Local', '00:55', 'Test Remark', '0', 1, 1, '2024-08-14 12:18:15', '2024-08-14 12:18:15'),
(20, '2', 2, 3, 'test01', 'test1', 444.00, 'Honda', 'testco1', 'testgiar1', 'Local', '01:00', 'Test Remark', '0', 1, 1, '2024-08-14 17:36:16', '2024-08-14 17:36:16'),
(21, '3', 3, 3, 'test2', 'testname2', 666.00, 'Honda', 'Joyti Sahu2', 'Ankit Sahu2', 'Local', '01:05', 'Test Remark 33333', '0', 1, 1, '2024-08-14 17:39:12', '2024-08-14 17:39:12'),
(22, '3', 4, 1, 'test03', 'testname3', 555.00, 'Honda', 'testco3', 'testguar3', 'Local', '01:05', 'Test Remark', '0', 1, 1, '2024-08-14 17:41:02', '2024-08-14 17:41:02'),
(23, '3', 2, 5, 'test04', 'testname4', 3324.00, 'Honda', 'testvco4', 'testgua4', 'Outstation', '00:30', 'Test Remark', '0', 1, 1, '2024-08-14 17:43:02', '2024-08-14 17:43:02'),
(24, '1', 2, 3, 'test05', 'testname05', 1055.00, 'Honda', 'Joyti Sahu', 'Ankit Sahu', 'Local', '01:05', 'Test Remark', '0', 1, 1, '2024-08-14 17:46:18', '2024-08-14 17:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `cases_fi_types`
--

CREATE TABLE `cases_fi_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `fi_type_id` int(11) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `pincode` int(11) NOT NULL,
  `land_mark` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cases_fi_types`
--

INSERT INTO `cases_fi_types` (`id`, `case_id`, `fi_type_id`, `mobile`, `address`, `pincode`, `land_mark`, `user_id`, `created_at`, `updated_at`) VALUES
(25, 18, 1, '9876543211', 'RVAddress1', 2013011, 'Landmark1', 1, '2024-08-14 12:16:42', '2024-08-14 12:16:42'),
(26, 18, 2, '9876543212', 'BVAddress2', 2013012, 'Landmark2', 1, '2024-08-14 12:16:42', '2024-08-14 12:16:42'),
(27, 19, 1, '9876543211', 'RVAddress4', 2013011, 'Landmark1', 1, '2024-08-14 12:18:15', '2024-08-14 12:18:15'),
(28, 20, 1, '9876543211', 'TestRVaddress1', 2013033, 'Landmark1', 3, '2024-08-14 17:36:16', '2024-08-14 17:36:16'),
(29, 20, 2, '9876583212', 'BVAddress1', 2013012, 'Landmark2', 3, '2024-08-14 17:36:16', '2024-08-14 17:36:16'),
(30, 21, 1, '9876543211', 'RVAddress2', 2013012, 'RVLandmark1', 2, '2024-08-14 17:39:12', '2024-08-14 17:39:12'),
(31, 21, 2, '9876543212', 'BVAddress2', 2013034, 'BVLandmark2', 2, '2024-08-14 17:39:12', '2024-08-14 17:39:12'),
(32, 22, 1, '9876543211', 'RVAddress3', 123456, 'RvLandmark3', 4, '2024-08-14 17:41:02', '2024-08-14 17:41:02'),
(33, 22, 2, '9876543212', 'BVAddress3', 2013012, 'Landmark2', 3, '2024-08-14 17:41:02', '2024-08-14 17:41:02'),
(34, 23, 1, '9876543211', 'RVAddress4', 2013011, 'Landmark14', 2, '2024-08-14 17:43:02', '2024-08-14 17:43:02'),
(35, 23, 2, '9876543212', 'BVAddress4', 567890, 'BVLandmark2', 4, '2024-08-14 17:43:02', '2024-08-14 17:43:02'),
(36, 24, 1, '9876543211', 'RVAddress5', 2013011, 'Landmark1', 3, '2024-08-14 17:46:18', '2024-08-14 17:46:18'),
(37, 24, 2, '9876543212', 'Address5', 2013012, 'Landmark2', 3, '2024-08-14 17:46:18', '2024-08-14 17:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fi_types`
--

CREATE TABLE `fi_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fi_types`
--

INSERT INTO `fi_types` (`id`, `name`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'RV', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(2, 'BV', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(3, 'TV', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(4, 'PV', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(5, 'av', '0', 1, '2024-08-06 06:37:14', 1, '2024-08-06 06:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_fi_type_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2020_07_24_184706_create_permission_tables', 1),
(7, '2020_09_12_043205_create_admins_table', 1),
(8, '2024_07_21_063128_create_products_table', 1),
(9, '2024_07_21_065533_create_banks_table', 1),
(10, '2024_07_22_031455_create_bank_product_mappings_table', 1),
(11, '2024_07_28_095400_create_application_type', 1),
(12, '2024_08_04_103747_create_cases_table', 1),
(13, '2024_08_04_103903_create_cases_fi_types_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'admin', 'dashboard', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(2, 'dashboard.edit', 'admin', 'dashboard', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(3, 'case.create', 'admin', 'case', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(4, 'case.view', 'admin', 'case', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(5, 'case.edit', 'admin', 'case', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(6, 'case.delete', 'admin', 'case', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(7, 'case.approve', 'admin', 'case', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(8, 'report.create', 'admin', 'report', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(9, 'report.view', 'admin', 'report', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(10, 'report.edit', 'admin', 'report', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(11, 'report.delete', 'admin', 'report', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(12, 'bank.create', 'admin', 'bank', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(13, 'bank.view', 'admin', 'bank', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(14, 'bank.edit', 'admin', 'bank', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(15, 'bank.delete', 'admin', 'bank', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(16, 'product.create', 'admin', 'product', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(17, 'product.view', 'admin', 'product', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(18, 'product.edit', 'admin', 'product', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(19, 'product.delete', 'admin', 'product', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(20, 'fitype.create', 'admin', 'fitype', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(21, 'fitype.view', 'admin', 'fitype', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(22, 'fitype.edit', 'admin', 'fitype', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(23, 'fitype.delete', 'admin', 'fitype', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(24, 'admin.create', 'admin', 'admin', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(25, 'admin.view', 'admin', 'admin', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(26, 'admin.edit', 'admin', 'admin', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(27, 'admin.delete', 'admin', 'admin', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(28, 'admin.approve', 'admin', 'admin', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(29, 'user.create', 'admin', 'user', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(30, 'user.view', 'admin', 'user', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(31, 'user.edit', 'admin', 'user', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(32, 'user.delete', 'admin', 'user', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(33, 'user.approve', 'admin', 'user', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(34, 'role.create', 'admin', 'role', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(35, 'role.view', 'admin', 'role', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(36, 'role.edit', 'admin', 'role', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(37, 'role.delete', 'admin', 'role', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(38, 'role.approve', 'admin', 'role', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(39, 'profile.view', 'admin', 'profile', '2024-08-05 15:29:11', '2024-08-05 15:29:11'),
(40, 'profile.edit', 'admin', 'profile', '2024-08-05 15:29:11', '2024-08-05 15:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `status` enum('0','1','2') NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `product_code`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'Personal Loan', 'PL', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(2, 'Home Loan', 'HL', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(3, 'Auto Loan', 'AL', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(4, 'BSV', 'BSV', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11'),
(5, 'ITR', 'ITR', '1', 1, '2024-08-05 15:29:11', 1, '2024-08-05 15:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'admin', '2024-08-05 15:29:11', '2024-08-05 15:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `api_token` varchar(80) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `admin_id`, `name`, `email`, `email_verified_at`, `password`, `api_token`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Maniruzzaman Akash', 'manirujjamanakash@gmail.com', NULL, '$2y$10$/YS.xcozyIE3S/bzpyIdr.Lr98EjvJ7cY..Xa6/.aSO3GSwTfAzWO', '045b58b159d307e57dc27d9a077523b6315a8560ba162259a1ff593c0aa5', NULL, '2024-08-05 15:29:11', '2024-08-14 17:47:31'),
(2, 1, 'testFA', 'testfa@gmail.com', NULL, '$2y$10$15FX5crfth6cf11KX6gku.y7HW/MgkAEwezE5F0xu/P6M3n.ipqIK', NULL, NULL, '2024-08-14 12:29:13', '2024-08-14 12:29:13'),
(3, 1, 'nupur', 'testnupur@gmail.com', NULL, '$2y$10$OU5R0aHJgSfDkcvXrZZEmO3ElnfzIqDv7aZewPHYkZyzpBtKv8VMG', 'f15fc40158d4233597e11552d6e92056faa35eaa202a7e2aab18ca144a40', NULL, '2024-08-14 17:33:28', '2024-08-14 17:44:45'),
(4, 1, 'raman', 'testraman@gmail.com', NULL, '$2y$10$u8Dl8enRL1TeP2in85CCxOLbWHEBWIpTWXy8F0wcxQZYOgPjCCeC6', NULL, NULL, '2024-08-14 17:34:24', '2024-08-14 17:34:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Indexes for table `application_types`
--
ALTER TABLE `application_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_product_mappings`
--
ALTER TABLE `bank_product_mappings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_product_mappings_bank_id_foreign` (`bank_id`),
  ADD KEY `bank_product_mappings_product_id_foreign` (`product_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cases_fi_types`
--
ALTER TABLE `cases_fi_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cases_fi_types_case_id_foreign` (`case_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fi_types`
--
ALTER TABLE `fi_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `application_types`
--
ALTER TABLE `application_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bank_product_mappings`
--
ALTER TABLE `bank_product_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `cases_fi_types`
--
ALTER TABLE `cases_fi_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fi_types`
--
ALTER TABLE `fi_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank_product_mappings`
--
ALTER TABLE `bank_product_mappings`
  ADD CONSTRAINT `bank_product_mappings_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_product_mappings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cases_fi_types`
--
ALTER TABLE `cases_fi_types`
  ADD CONSTRAINT `cases_fi_types_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
