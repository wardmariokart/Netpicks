-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Nov 22, 2020 at 11:21 AM
-- Server version: 5.7.32
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `planItdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `NightTypes`
--

CREATE TABLE `NightTypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `displayName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `NightTypes`
--

INSERT INTO `NightTypes` (`id`, `name`, `displayName`) VALUES
(1, 'solo', 'me, myself and I'),
(2, 'boysNight', 'boys night 🍻'),
(3, 'dateNight', 'date night 🌹'),
(4, 'girlsNight', 'girls night 🍷');

-- --------------------------------------------------------

--
-- Table structure for table `StepOneMovieOptions`
--

CREATE TABLE `StepOneMovieOptions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'How option is identified in code',
  `displayName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'How option is displayed to user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `StepOneMovieOptions`
--

INSERT INTO `StepOneMovieOptions` (`id`, `name`, `displayName`) VALUES
(1, 'cry', 'wenen in een hoekje'),
(2, 'action', 'vol adrenaline zitten'),
(3, 'scary', 'me verstoppen onder mijn deken');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `NightTypes`
--
ALTER TABLE `NightTypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `StepOneMovieOptions`
--
ALTER TABLE `StepOneMovieOptions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `NightTypes`
--
ALTER TABLE `NightTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `StepOneMovieOptions`
--
ALTER TABLE `StepOneMovieOptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
