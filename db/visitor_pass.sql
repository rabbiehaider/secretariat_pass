-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2026 at 11:55 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visitor_pass`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `status`) VALUES
(1, 'Cabinet Division', 1),
(2, 'Public Administration', 1),
(3, 'Finance Division', 1),
(4, 'Home Affairs', 1),
(5, 'ICT Division', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gate_logs`
--

CREATE TABLE `gate_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `application_id` int(10) UNSIGNED DEFAULT NULL,
  `pass_no` varchar(40) DEFAULT NULL,
  `qr_token` varchar(80) NOT NULL,
  `scanned_by` int(10) UNSIGNED DEFAULT NULL,
  `scan_status` enum('valid','invalid','expired','already_used','rejected','pending') NOT NULL,
  `entry_time` datetime NOT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gate_logs`
--

INSERT INTO `gate_logs` (`id`, `application_id`, `pass_no`, `qr_token`, `scanned_by`, `scan_status`, `entry_time`, `remarks`) VALUES
(1, 1, 'VP-20260614-0001', 'ec7670a9ea2cd5dc2d3e6e2797ae0dbdda1616d74e104b3b', 1, 'valid', '2026-06-14 11:18:53', 'Entry allowed'),
(2, NULL, NULL, 'ec7670a9ea2cd5dc2d3e6e2797ae0dbdda1616d04b3b', 1, 'invalid', '2026-06-14 11:18:59', 'Token not found'),
(3, 1, 'VP-20260614-0001', 'ec7670a9ea2cd5dc2d3e6e2797ae0dbdda1616d74e104b3b', 1, 'already_used', '2026-06-14 11:19:03', 'Duplicate entry attempt'),
(4, NULL, NULL, 'b42eec51bb6743183d69901ebe306718fe5a76​25008f4ffd', 2, 'invalid', '2026-06-14 11:38:22', 'Token not found'),
(5, NULL, NULL, '​b42eec51bb6743183d69901ebe306718fe5a76​25008f4ffd', 2, 'invalid', '2026-06-14 11:38:28', 'Token not found'),
(6, 4, 'VP-20260614-0003', 'b42eec51bb6743183d69901ebe306718fe5a7625008f4ffd', 2, 'expired', '2026-06-14 11:39:43', 'Visit date mismatch'),
(7, 5, 'VP-20260614-0004', '4ad9ae3b87beb4c563d5e5982f26d908b3a0cbcfabbcaa24', 2, 'valid', '2026-06-14 11:41:07', 'Entry allowed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','gate') NOT NULL DEFAULT 'admin',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'System Admin', 'admin@secretariat.local', '$2y$10$X0Od0FlcWseWma5tA.G1P.JNChSHcCKg//MUcNUap3.swFpOrXXPW', 'admin', 1, '2026-06-14 14:54:57'),
(2, 'Gate Officer', 'gate@secretariat.local', '$2y$10$X0Od0FlcWseWma5tA.G1P.JNChSHcCKg//MUcNUap3.swFpOrXXPW', 'gate', 1, '2026-06-14 14:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_applications`
--

CREATE TABLE `visitor_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `pass_no` varchar(40) DEFAULT NULL,
  `qr_token` varchar(80) DEFAULT NULL,
  `name` varchar(160) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `nid` varchar(60) NOT NULL,
  `address` mediumtext NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `visit_to` varchar(160) NOT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `visit_date` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_reason` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitor_applications`
--

INSERT INTO `visitor_applications` (`id`, `pass_no`, `qr_token`, `name`, `phone`, `nid`, `address`, `purpose`, `visit_to`, `department_id`, `visit_date`, `photo`, `status`, `approved_by`, `approved_at`, `rejected_reason`, `created_at`, `updated_at`) VALUES
(1, 'VP-20260614-0001', 'ec7670a9ea2cd5dc2d3e6e2797ae0dbdda1616d74e104b3b', 'Atik Rahman', '01874878787', '1524124', 'Test dfg mbn hgm', 'Testisn dfc,g msngcmb', '1', 1, '2026-06-14', 'uploads/visitors/cc8bb698f6ce9f1505f3578de9c705ab.png', 'approved', 1, '2026-06-14 11:16:04', NULL, '2026-06-14 11:14:34', '2026-06-14 11:16:04'),
(2, NULL, NULL, 'Shamim Hossain', '25411541', '4144445414564565', '7v457bryjgn', 'v456754', '21454511164', 1, '2026-06-14', 'uploads/visitors/29f0fbb7e2173eb3b38823859074926c.jpg', 'rejected', NULL, NULL, 'Rejected by admin', '2026-06-14 11:22:03', '2026-06-14 11:22:55'),
(3, 'VP-20260614-0002', '96505c478557f213bc136355022c1ff6af994c0d07c02a3d', 'AEjnt bnbn', 'skgtjhsbhn', 'wekt hn', '6756756', '5675', '56756', 4, '2026-06-14', 'uploads/visitors/985cb29e00f71e8cf655f5e46e02fe6a.png', 'approved', 2, '2026-06-14 11:23:47', NULL, '2026-06-14 11:23:38', '2026-06-14 11:23:47'),
(4, 'VP-20260614-0003', 'b42eec51bb6743183d69901ebe306718fe5a7625008f4ffd', 'Tes tgyfg', 'hgvn v', 'h fgj hg', 'd hgf', 'hfg v', 'gnv', 4, '2026-06-25', 'uploads/visitors/05334875d4c96f6f3768bdac3ba4b631.png', 'approved', 2, '2026-06-14 11:35:41', NULL, '2026-06-14 11:35:16', '2026-06-14 11:35:41'),
(5, 'VP-20260614-0004', '4ad9ae3b87beb4c563d5e5982f26d908b3a0cbcfabbcaa24', 'Tes tdrh g', '01234567890', 'v fg', 'vbfjgyjgymuygnj', 'n ghgv', 'v g hgv', 3, '2026-06-14', 'uploads/visitors/8838372758b5e3310986049d816b70e9.png', 'approved', 2, '2026-06-14 11:40:21', NULL, '2026-06-14 11:40:08', '2026-06-14 11:40:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gate_logs`
--
ALTER TABLE `gate_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gate_application` (`application_id`),
  ADD KEY `idx_gate_entry_time` (`entry_time`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- Indexes for table `visitor_applications`
--
ALTER TABLE `visitor_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_visitor_pass_no` (`pass_no`),
  ADD UNIQUE KEY `uq_visitor_qr_token` (`qr_token`),
  ADD KEY `idx_visitor_status` (`status`),
  ADD KEY `idx_visitor_visit_date` (`visit_date`),
  ADD KEY `fk_visitor_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gate_logs`
--
ALTER TABLE `gate_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitor_applications`
--
ALTER TABLE `visitor_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gate_logs`
--
ALTER TABLE `gate_logs`
  ADD CONSTRAINT `fk_gate_application` FOREIGN KEY (`application_id`) REFERENCES `visitor_applications` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `visitor_applications`
--
ALTER TABLE `visitor_applications`
  ADD CONSTRAINT `fk_visitor_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
