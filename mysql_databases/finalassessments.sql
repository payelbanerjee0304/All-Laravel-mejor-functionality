-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2024 at 12:54 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `finalassessments`
--

CREATE TABLE `finalassessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `auth` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finalassessments`
--

INSERT INTO `finalassessments` (`id`, `name`, `email`, `password`, `phone`, `gender`, `language`, `image`, `user`, `auth`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 1234567890, 'Female', 'English, Hindi, Bengali', './upload/1705491436_sunflower.jpg', 'admin', '0', NULL, NULL),
(2, 'payel banerjee', 'payel@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 8777644951, 'Female', 'English, Hindi, Bengali', './upload/1705491943_sunflower.jpg', 'client', '0', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `finalassessments`
--
ALTER TABLE `finalassessments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `finalassessments`
--
ALTER TABLE `finalassessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
