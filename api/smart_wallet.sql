-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 09, 2023 at 12:21 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_wallet`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'test 1'),
(2, 'test 2'),
(3, 'test 3'),
(4, 'test 4');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `proccess` varchar(1) NOT NULL DEFAULT '+',
  `cat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `money`, `proccess`, `cat_id`) VALUES
(4, 2, 2, '+', NULL),
(5, 1, 2, '-', NULL),
(6, 2, 3, '+', NULL),
(7, 1, 3, '-', NULL),
(8, 2, 1, '+', NULL),
(9, 1, 1, '-', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(100) NOT NULL,
  `money` int(11) NOT NULL DEFAULT '0',
  `user_type` char(1) NOT NULL DEFAULT 'p',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `password`, `money`, `user_type`, `user_id`) VALUES
(1, 'Mohamed El-Mohands', '01234567890', '$2y$10$offNE2lg1NdtowVCfHuuGOdE9hpr68r3Eg3ZoN1Xz0PBcQBjkDX2G', 4, 'p', NULL),
(2, 'test user', '01234567899', '$2y$10$wUhu8MB51wnsvEEz6XwMY.bUMJudYHTpsDvu0Wmzzr3oBnFSeYGtq', 6, 'p', NULL),
(3, 'test user2', '0123456788', '$2y$10$tC4zzc/p0R1lAqR289fmhOl6fI4VTYGQ6uR2hKSrhISi53FyXGYFO', 0, 'p', NULL),
(4, 'test user3', '01234567880', '$2y$10$qUamDXTozs/pVTMuNv41teaJo11AtxHXykXjZ2ZXZ.ygsvLKEjtKG', 0, 'p', NULL),
(10, 'test user5', '01234567882', '$2y$10$9IVz.BSjerX2p6X39Q2ePOAuz8XrMfpqDNIkoiIJFr4Dxh11xGPB2', 0, 'p', NULL),
(11, 'sub test user', '0111100000', '$2y$10$auUuhUB4PDEbUyjWmg/2x.t55jrHPs2Y3Qrw4mbAnLVa4x4fAqOZ2', 0, 'c', 10);

-- --------------------------------------------------------

--
-- Table structure for table `user_cats`
--

CREATE TABLE `user_cats` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_cats`
--

INSERT INTO `user_cats` (`id`, `user_id`, `cat_id`, `allow`) VALUES
(4, 11, 1, 1),
(5, 11, 2, 1),
(6, 11, 3, 1),
(7, 11, 4, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk` (`user_id`),
  ADD KEY `cat_trans_fk` (`cat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_user_fk` (`user_id`);

--
-- Indexes for table `user_cats`
--
ALTER TABLE `user_cats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_user_cat_fk` (`user_id`),
  ADD KEY `cat_user_cat_fk` (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_cats`
--
ALTER TABLE `user_cats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `cat_trans_fk` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_cats`
--
ALTER TABLE `user_cats`
  ADD CONSTRAINT `cat_user_cat_fk` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_user_cat_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
