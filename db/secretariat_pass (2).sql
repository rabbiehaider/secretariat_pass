-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2026 at 04:32 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secretariat_pass`
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
(7, 5, 'VP-20260614-0004', '4ad9ae3b87beb4c563d5e5982f26d908b3a0cbcfabbcaa24', 2, 'valid', '2026-06-14 11:41:07', 'Entry allowed'),
(8, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(9, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(10, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(11, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(12, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(13, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(14, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:53', 'Visit date mismatch'),
(15, 10, 'VP-20260621-0001', 'D45CE030D2', 1, 'expired', '2026-06-21 16:41:54', 'Visit date mismatch'),
(16, 12, 'VP-20260623-0001', 'C99DAD48DD', NULL, 'expired', '2026-06-23 20:52:19', 'Visit date mismatch'),
(17, 13, 'VP-20260623-0002', 'D285638BD5', NULL, 'valid', '2026-06-23 20:56:46', 'Entry allowed'),
(18, 14, 'VP-20260623-0003', 'E57935C192', 1, 'valid', '2026-06-23 23:50:15', 'Entry allowed');

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
(1, 'System Admin', 'admin@gmail.com', '$2y$10$X0Od0FlcWseWma5tA.G1P.JNChSHcCKg//MUcNUap3.swFpOrXXPW', 'admin', 1, '2026-06-14 14:54:57'),
(2, 'Gate Officer', 'gate@gmail.com', '$2y$10$X0Od0FlcWseWma5tA.G1P.JNChSHcCKg//MUcNUap3.swFpOrXXPW', 'gate', 1, '2026-06-14 14:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_applications`
--

CREATE TABLE `visitor_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `visitor_id` int(10) UNSIGNED DEFAULT NULL,
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

INSERT INTO `visitor_applications` (`id`, `visitor_id`, `pass_no`, `qr_token`, `name`, `phone`, `nid`, `address`, `purpose`, `visit_to`, `department_id`, `visit_date`, `photo`, `status`, `approved_by`, `approved_at`, `rejected_reason`, `created_at`, `updated_at`) VALUES
(1, NULL, 'VP-20260614-0001', 'ec7670a9ea2cd5dc2d3e6e2797ae0dbdda1616d74e104b3b', 'Atik Rahman', '01874878787', '1524124', 'Test dfg mbn hgm', 'Testisn dfc,g msngcmb', '1', 1, '2026-06-14', 'uploads/visitors/cc8bb698f6ce9f1505f3578de9c705ab.png', 'approved', 1, '2026-06-14 11:16:04', NULL, '2026-06-14 11:14:34', '2026-06-14 11:16:04'),
(2, NULL, NULL, NULL, 'Shamim Hossain', '25411541', '4144445414564565', '7v457bryjgn', 'v456754', '21454511164', 1, '2026-06-14', 'uploads/visitors/29f0fbb7e2173eb3b38823859074926c.jpg', 'rejected', NULL, NULL, 'Rejected by admin', '2026-06-14 11:22:03', '2026-06-14 11:22:55'),
(3, NULL, 'VP-20260614-0002', '96505c478557f213bc136355022c1ff6af994c0d07c02a3d', 'AEjnt bnbn', 'skgtjhsbhn', 'wekt hn', '6756756', '5675', '56756', 4, '2026-06-14', 'uploads/visitors/985cb29e00f71e8cf655f5e46e02fe6a.png', 'approved', 2, '2026-06-14 11:23:47', NULL, '2026-06-14 11:23:38', '2026-06-14 11:23:47'),
(4, NULL, 'VP-20260614-0003', 'b42eec51bb6743183d69901ebe306718fe5a7625008f4ffd', 'Tes tgyfg', 'hgvn v', 'h fgj hg', 'd hgf', 'hfg v', 'gnv', 4, '2026-06-25', 'uploads/visitors/05334875d4c96f6f3768bdac3ba4b631.png', 'approved', 2, '2026-06-14 11:35:41', NULL, '2026-06-14 11:35:16', '2026-06-14 11:35:41'),
(5, NULL, 'VP-20260614-0004', '4ad9ae3b87beb4c563d5e5982f26d908b3a0cbcfabbcaa24', 'Tes tdrh g', '01234567890', 'v fg', 'vbfjgyjgymuygnj', 'n ghgv', 'v g hgv', 3, '2026-06-14', 'uploads/visitors/8838372758b5e3310986049d816b70e9.png', 'approved', 2, '2026-06-14 11:40:21', NULL, '2026-06-14 11:40:08', '2026-06-14 11:40:21'),
(6, NULL, NULL, NULL, 'Rakib Hassan', '01514151515', '1514151515', '567', '567', '5654', 3, '2026-06-19', 'uploads/visitors/e098fb223ca183763bc9dd2d7e20595f.png', 'rejected', NULL, NULL, 'Rejected by admin', '2026-06-19 21:57:16', '2026-06-21 10:16:04'),
(7, NULL, 'VP-20260621-0002', '2011846A0C', 'Rakib Hassan', 's gdfg', 'hfgh fgh', '78', '7', '76', 1, '2026-06-19', 'uploads/visitors/db002fbdf68b975eee6205f84647217e.png', 'approved', 1, '2026-06-21 10:15:53', NULL, '2026-06-19 21:59:13', '2026-06-21 10:15:53'),
(8, NULL, 'VP-20260619-0001', 'be0d174f4885bce851b25c71a80229c24ece465769f77d20', 'Rakib Hassan', 'tert', 'ret', 'rty', 'rty', 'tryrty', 3, '2026-06-19', 'uploads/visitors/70f0559a96506ee5ebbf57426c29f369.png', 'approved', 1, '2026-06-19 22:21:30', NULL, '2026-06-19 22:21:01', '2026-06-19 22:21:30'),
(9, NULL, 'VP-20260619-0002', 'e369f35459296fe97a73499af04e7d1b2c94a95f15b34fad', 'Rakib Hassan', '01514151516', '1514151515', 'dsfsdf', 'sdf', 'asdfsdf', 1, '2026-06-25', 'uploads/visitors/10d4e0c1ee5171faf030b835be84f487.png', 'approved', 1, '2026-06-19 23:15:33', NULL, '2026-06-19 23:15:09', '2026-06-19 23:15:33'),
(10, 1, 'VP-20260621-0001', 'D45CE030D2', 'Lalon Hossain', '01415161718', '1915161482', '01415161718', 'Testindfgfdg', 'Test  gdfh', 4, '2026-06-26', 'uploads/visitors/1a7e69b18f63928534893bf822906079.png', 'approved', 1, '2026-06-21 10:15:37', NULL, '2026-06-21 10:14:21', '2026-06-21 10:15:37'),
(11, 2, 'VP-20260622-0001', 'CE4CC7460A', 'Rakib Hossain', '01627849931', '12345547', 'Char Lawrence, kamalnagar, lakshmipur', 'for giving money', 'Asraf Khan', 5, '2026-06-21', 'uploads/visitors/visitor_6a37fcc57fa0f.png', 'approved', 1, '2026-06-22 00:07:33', NULL, '2026-06-21 21:01:25', '2026-06-22 00:07:33'),
(12, 3, 'VP-20260623-0001', 'C99DAD48DD', 'Rabbie Haider', '01781326534', '1936134252', 'Mirpur-2, Dhaka-1216', 'For important Meeting', 'Jakir Islam', 3, '2026-06-25', 'uploads/visitors/visitor_6a3a9c435fe3c.png', 'approved', 1, '2026-06-23 20:49:57', NULL, '2026-06-23 20:46:27', '2026-06-23 20:49:57'),
(13, 3, 'VP-20260623-0002', 'D285638BD5', 'Rabbie Haider', '01781326534', '1936134252', 'Mirpur-2, Dhaka-1216', 'Testing Testing', 'Mizan Rahman', 4, '2026-06-23', 'uploads/visitors/visitor_6a3a9e1bea3a2.png', 'approved', 1, '2026-06-23 20:54:47', NULL, '2026-06-23 20:54:19', '2026-06-23 20:54:47'),
(14, 3, 'VP-20260623-0003', 'E57935C192', 'Rabbie Haider', '01781326534', '1936134252', 'Mirpur-2, Dhaka-1216', 'Meeting with Jamil Sarkar', 'Jamil Sarkar', 3, '2026-06-23', 'uploads/visitors/visitor_6a3ac689ba459.jpg', 'approved', 1, '2026-06-23 23:47:24', NULL, '2026-06-23 23:46:49', '2026-06-23 23:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_users`
--

CREATE TABLE `visitor_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `email` varchar(160) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nid` varchar(60) NOT NULL,
  `address` mediumtext NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `last_login_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `visitor_users`
--

INSERT INTO `visitor_users` (`id`, `name`, `email`, `phone`, `password`, `nid`, `address`, `photo`, `status`, `created_at`, `last_login_at`) VALUES
(1, 'Lalon Hossain', 'lal@gmail.com', '01415161718', '$2y$10$YYszY2Tb/TJ.wBdgY8wk7u.ZhNATcsCEozlf.qEtMoHYspOXF1Z6q', '1915161482', '01415161718', NULL, 1, '2026-06-21 10:13:34', '2026-06-22 00:06:51'),
(2, 'Rakib Hossain', 'hossainrakib216@gmail.com', '01627849931', '$2y$10$HpqP292WqU45xZnGJpTVJOa.YIvcmchWAu6hadyV7w3gMm7aY8MS6', '12345547', 'Char Lawrence, kamalnagar, lakshmipur', NULL, 1, '2026-06-21 21:00:05', '2026-06-22 22:30:55'),
(3, 'Rabbie Haider', 'rabbiehaider@gmail.com', '01781326534', '$2y$10$cCAXySCgZYySnYxliPNuc.Y3ukAD84BTe/1WJvg62Ezmw3W0jH.AW', '1936134252', 'Mirpur-2, Dhaka-1216', NULL, 1, '2026-06-23 20:44:17', '2026-06-24 00:01:36');

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
  ADD KEY `idx_visitor_applications_visitor_id` (`visitor_id`),
  ADD KEY `fk_visitor_department` (`department_id`);

--
-- Indexes for table `visitor_users`
--
ALTER TABLE `visitor_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_visitor_users_email` (`email`),
  ADD UNIQUE KEY `uq_visitor_users_phone` (`phone`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitor_applications`
--
ALTER TABLE `visitor_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `visitor_users`
--
ALTER TABLE `visitor_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `fk_visitor_applications_visitor_user` FOREIGN KEY (`visitor_id`) REFERENCES `visitor_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_visitor_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
