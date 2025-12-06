-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 01:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patient_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact` varchar(30) NOT NULL,
  `imaging` varchar(255) DEFAULT NULL,
  `referred_by_id` int(11) DEFAULT NULL,
  `aadhaar` varchar(25) DEFAULT NULL,
  `fees` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `age`, `gender`, `contact`, `imaging`, `referred_by_id`, `aadhaar`, `fees`, `discount`, `notes`, `created_at`) VALUES
(1, 'Sudeep Pandey', 20, 'Male', '6t54yt56467y57', '63546547', 1, 't54y65765847868', 654.00, 657.00, '6y757uy56uyjh', '2025-12-05 09:19:27'),
(2, 'Sudeep Pandey', 111, 'Male', '21231321322', '', 1, '13232323244', 212.00, 22.00, '321212121212121321', '2025-12-05 10:18:47'),
(3, 'qswqd', 33, 'Male', '221222222222', '', 2, '222222222222', 2222.00, 222.00, 'dsfvdgdf', '2025-12-05 10:22:45'),
(4, 'Sudeep Pandey', 22, 'Male', '2132435345', NULL, 1, '234243535', 33333.00, 333.00, '34fsvfdgbdf', '2025-12-05 10:29:31'),
(5, 'Sudeep Pandey', 55, 'Male', '234435435', 'IMG_1764930930_7071.png', 2, '34325454624425', 44444.00, 444.00, 'fdgbfbhtf', '2025-12-05 10:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `referrers`
--

CREATE TABLE `referrers` (
  `id` int(11) NOT NULL,
  `type` enum('doctor','asha') NOT NULL,
  `name` varchar(120) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `hospital_clinic` varchar(180) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referrers`
--

INSERT INTO `referrers` (`id`, `type`, `name`, `phone`, `hospital_clinic`, `address`, `created_at`) VALUES
(1, 'doctor', 'Sudeep Pandey', '07467867354', 'fsdfggh', 'Nayepura jaitpur kalan bah agra', '2025-12-05 09:18:05'),
(2, 'asha', 'mmm', '365846365983', 'yrrgt', 'fggfffff', '2025-12-05 09:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@example.com', '$2y$10$QIXYHdSdFe4MWbrTzPgpUO5auKMI3xmw0Lsx3e1T35RtBZecqPLUW', 'admin', '2025-12-05 06:16:01'),
(2, 'Employee One', 'emp@example.com', '$2y$10$p62zKNLgLb.CN2ctmD5E6.62AQjYr/XSzVHbCCIpY/.ZA9CK1c.Kq', 'employee', '2025-12-05 06:16:01'),
(3, 'sudeep', 'sudeeppandey61@gmail.com', 'admin123', 'admin', '2025-12-05 06:56:09'),
(5, 'Sudeep', 'sudeep@gmail.com', '$2y$10$vJBs4kZS6WuFrb2xp87N4.imR4IHk/RYV1zs3RFOAz0MsfcbVs386', 'employee', '2025-12-05 10:12:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patients_ref` (`referred_by_id`);

--
-- Indexes for table `referrers`
--
ALTER TABLE `referrers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `referrers`
--
ALTER TABLE `referrers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patients_ref` FOREIGN KEY (`referred_by_id`) REFERENCES `referrers` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
